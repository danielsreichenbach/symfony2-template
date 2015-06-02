<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides fixtures for testing users and user permissions
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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

        /** @var \AppBundle\Entity\User $administrator */
        $administrator = $userManager->createUser();
        $administrator->setUsername('administrator');
        $administrator->setEmail('admin@symfony2-template.io');
        $administrator->setPlainPassword('administrator');
        $administrator->setEnabled(true);
        $administrator->setSuperAdmin(true);
        $administrator->setLocale('en');

        $userManager->updateUser($administrator, true);
        $this->addReference('user-administrator', $administrator);

        /** @var \AppBundle\Entity\User $defaultUser */
        $defaultUser = $userManager->createUser();
        $defaultUser->setUsername('user');
        $defaultUser->setEmail('user@symfony2-template.io');
        $defaultUser->setPlainPassword('user');
        $defaultUser->setEnabled(true);
        $defaultUser->setLocale('de');

        $userManager->updateUser($defaultUser, true);
        $this->addReference('user-default', $defaultUser);

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 20;
    }
}
