<?php

namespace AppBundle\Command;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Sends out reminders to unconfirmed accounts
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class RemindConfirmationsCommand extends ContainerAwareCommand
{
    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * Set up the command and its' parameters
     */
    protected function configure()
    {
        $this
            ->setName('app:users:remind')
            ->setDescription('Send user confirmation emails')
            ->setHelp($this->getCommandHelp());
    }

    /**
     * This method is executed before the the execute() method. It's main purpose
     * is to initialize the variables used in the rest of the command methods.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->entityManager = $this->getContainer()->get('doctrine')->getManager();
    }

    /**
     * This method is executed after interact() and initialize(). It usually
     * contains the logic to execute to complete this command task.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $context = $this->getContainer()->get('router')->getContext();
        $context->setHost($this->getContainer()->getParameter('app.request_context.host'));
        $context->setScheme($this->getContainer()->getParameter('app.request_context.scheme'));

        $users = $this->entityManager->getRepository('AppBundle:User')->findUnconfirmedUsers();
        $usersReminded = 0;

        /**
         * @var \FOS\UserBundle\Mailer\MailerInterface
         */
        $fosMail = $this->getContainer()->get('fos_user.mailer');

        $table = new Table($output);
        $table->setHeaders(array('ID', 'Email', 'Token'));

        foreach ($users as $user /** @var \AppBundle\Entity\User $user */) {
            $table->addRow(array($user->getId(), $user->getEmailCanonical(), $user->getConfirmationToken()));
            $fosMail->sendConfirmationEmailMessage($user);
            $usersReminded++;
        }

        if ($usersReminded > 0) {
            $table->render();
        } else {
            $output->writeln('<info>No user confirmations pending.</info>');
        }
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
The <info>%command.name%</info> command will send user account confirmation mails
to unconfirmed users.

  <info>php %command.full_name%</info>
HELP;
    }
}
