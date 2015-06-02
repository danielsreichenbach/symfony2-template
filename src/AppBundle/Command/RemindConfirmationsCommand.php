<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Sends out reminders to unconfirmed accounts
 *
 * @package AppBundle\Command
 */
class RemindConfirmationsCommand extends ContainerAwareCommand
{
    /**
     * Configure the command
     */
    protected function configure()
    {
        $this
            ->setName('app:users:remind')
            ->setDescription('Send user confirmation emails')
            ->setHelp($this->getCommandHelp());
    }

    /**
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

        /**
         * @var \FOS\UserBundle\Model\UserManagerInterface
         */
        $userManager = $this->getContainer()->get('fos_user.user_manager');

        /**
         * @var \FOS\UserBundle\Mailer\MailerInterface
         */
        $fosMail = $this->getContainer()->get('fos_user.mailer');

        $table = new Table($output);
        $table->setHeaders(array('ID', 'Email', 'Token'));

        $allUsers = $userManager->findUsers();
        $usersReminded = 0;

        foreach ($allUsers as $user) {
            /** @var \AppBundle\Entity\User $user */
            $confirmationToken = $user->getConfirmationToken();

            if (!empty($confirmationToken)) {
                $table->addRow(array($user->getId(), $user->getEmailCanonical(), $user->getConfirmationToken()));
                $fosMail->sendConfirmationEmailMessage($user);
                $usersReminded++;
            }
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
