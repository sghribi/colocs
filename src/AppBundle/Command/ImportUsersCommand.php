<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class ImportUsersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('colocs:import:users')
            ->setDescription('Import all CTI users.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ldapService = $this->getContainer()->get('app.service.ldap');
        $userService = $this->getContainer()->get('app.service.user');

        $ctiUids = $ldapService->getAllCtiUid();

        foreach ($ctiUids as $ctiUid) {
            try {
                $user = $userService->createOrUpdateUserByLogin($ctiUid);
                $output->writeln(sprintf('<info>User %s imported !</info>', $user->getCtiUid()));
            } catch (UsernameNotFoundException $e) {
                $output->writeln(sprintf('<comment>Unable to import user %s</comment>', $ctiUid));
            }
        }
    }
}
