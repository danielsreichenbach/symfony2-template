<?php

namespace AppBundle\Command;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * A command console that lists all the existing users. To use this command, open
 * a terminal window, enter into your project directory and execute the following:
 *     $ php app/console app:list-users
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class ListUsersCommand extends ContainerAwareCommand
{
    /**
     * @var ObjectManager
     */
    private $entityManager;

    protected function configure()
    {
        $this
            ->setName('app:users:list')
            ->setDescription('Lists all existing users')
            ->setHelp($this->getCommandHelp())
            ->addOption('max-results', null, InputOption::VALUE_OPTIONAL, 'Limits the number of users listed', 50)
            ->addOption(
                'send-to',
                null,
                InputOption::VALUE_OPTIONAL,
                'If set, the result is sent to the given email address'
            );
    }

    /**
     * This method is executed before the the execute() method. It's main purpose
     * is to initialize the variables used in the rest of the command methods.
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->entityManager = $this->getContainer()->get('doctrine')->getManager();
    }

    /**
     * This method is executed after initialize(). It usually contains the logic
     * to execute to complete this command task.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $maxResults = $input->getOption('max-results');
        // Limit the displayed amount by default
        $users = $this->entityManager->getRepository('AppBundle:User')->findEnabledUsers($maxResults);

        // Doctrine query returns an array of objects and we need an array of plain arrays
        $usersAsPlainArrays = array_map(
            function (
                /** @var \AppBundle\Entity\User $user */
                $user
            ) {
                return array($user->getId(), $user->getUsername(), $user->getEmail(), implode(', ', $user->getRoles()));
            },
            $users
        );

        $bufferedOutput = new BufferedOutput();
        $table          = new Table($bufferedOutput);
        $table
            ->setHeaders(array('ID', 'Username', 'Email', 'Roles'))
            ->setRows($usersAsPlainArrays);
        $table->render();
        // instead of displaying the table of users, store it in a variable
        $tableContents = $bufferedOutput->fetch();
        if (null !== $email = $input->getOption('send-to')) {
            $this->sendReport($users, $email);
        }
        $output->writeln($tableContents);
    }

    /**
     * Sends the given $contents to the $recipient email address.
     *
     * @param array $data
     * @param string $recipient
     */
    private function sendReport($data, $recipient)
    {
        $mailerAddress = $this->getContainer()->getParameter('app.mail.address');
        $mailerSenderName = $this->getContainer()->getParameter('app.mail.sender_name');
        $mailer = $this->getContainer()->get('mailer');
        $renderer = $this->getContainer()->get('templating');

        $message = $mailer->createMessage()
            ->setSubject(sprintf('User report (%s)', date('Y-m-d H:i:s')))
            ->setFrom(array($mailerAddress => $mailerSenderName))
            ->setTo($recipient)
            ->setBody($renderer->render('commands/list/users.html.twig', array('users' => $data)), 'text/html')
            ->addPart($renderer->render('commands/list/users.txt.twig', array('users' => $data)), 'text/plain');
        $mailer->send($message);
    }

    /**
     * The command help is usually included in the configure() method, but when
     * it's too long, it's better to define a separate method to maintain the
     * code readability.
     *
     * @return string
     */
    private function getCommandHelp()
    {
        return <<<HELP
The <info>%command.name%</info> command lists all the users registered in the application:
  <info>php %command.full_name%</info>
By default the command only displays the 50 most recent users. Set the number of
results to display with the <comment>--max-results</comment> option:
  <info>php %command.full_name%</info> <comment>--max-results=2000</comment>
In addition to displaying the user list, you can also send this information to
the email address specified in the <comment>--send-to</comment> option:
  <info>php %command.full_name%</info> <comment>--send-to=daniel@kogitoapp.com</comment>
HELP;
    }
}
