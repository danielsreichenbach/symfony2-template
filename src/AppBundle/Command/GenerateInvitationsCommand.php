<?php

namespace AppBundle\Command;

use AppBundle\Entity\Invitation;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generates invitation codes
 *
 * @package AppBundle\Command
 */
class GenerateInvitationsCommand extends ContainerAwareCommand
{
    /**
     * Configure the command
     */
    protected function configure()
    {
        $this
            ->setName('app:invitations:generate')
            ->setDescription('Generate invitation codes')
            ->setHelp($this->getCommandHelp())
            ->addOption(
                'amount',
                'a',
                InputOption::VALUE_OPTIONAL,
                'How many invitations do you want to generate?',
                1
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $inviteAmount  = $input->getOption('amount');

        $table = new Table($output);
        $table->setHeaders(array('#', 'Token'));

        for ($inviteNr = 1; $inviteNr <= $inviteAmount; $inviteNr++) {
            $invitation = new Invitation();
            $invitation->setSent(true);
            $entityManager->persist($invitation);
            $table->addRow(array($inviteNr, $invitation->getCode()));
        }
        $entityManager->flush();

        $table->render();
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
The <info>%command.name%</info> command will create invitation codes for user
registration

  <info>php %command.full_name%</info>

To generate a specific amount use provide <comment>--amount</comment>

  <info>php %command.full_name%</info> --amount 10

to create ten invitation codes.
HELP;
    }
}
