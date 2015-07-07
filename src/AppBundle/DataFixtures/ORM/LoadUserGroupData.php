<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides fixtures for testing user/group assignments
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class LoadUserGroupData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /**
         * @var \FOS\UserBundle\Model\UserManagerInterface
         */
        $userManager = $this->container->get('fos_user.user_manager');

        /** @var \AppBundle\Entity\Group $groupAdmins */
        $groupAdmins = $this->getReference('group-admin');
        /** @var \AppBundle\Entity\Group $groupUser */
        $groupUsers  = $this->getReference('group-user');

        /** @var \AppBundle\Entity\User $administrator */
        $administrator  = $this->getReference('user-administrator');
        /** @var \AppBundle\Entity\User $defaultUser */
        $defaultUser    = $this->getReference('user-default');
        /** @var \AppBundle\Entity\User $frenchUser */
        $frenchUser     = $this->getReference('user-french');
        /** @var \AppBundle\Entity\User $germanUser */
        $germanUser     = $this->getReference('user-german');

        $administrator->addGroup($groupAdmins);
        $userManager->updateUser($administrator, true);

        $defaultUser->addGroup($groupUsers);
        $userManager->updateUser($defaultUser, true);

        $frenchUser->addGroup($groupUsers);
        $userManager->updateUser($frenchUser, true);

        $germanUser->addGroup($groupUsers);
        $userManager->updateUser($germanUser, true);

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 30;
    }
}
