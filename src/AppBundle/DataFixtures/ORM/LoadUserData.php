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

        /**
         * @var \FOS\UserBundle\Util\TokenGeneratorInterface
         */
        $tokenGenerator = $this->container->get('fos_user.util.token_generator');

        /** @var \AppBundle\Entity\User $administrator */
        $administrator = $userManager->createUser();
        $administrator->setUsername('administrator');
        $administrator->setEmail('admin@symfony2-template.io');
        $administrator->setPlainPassword('test');
        $administrator->setEnabled(true);
        $administrator->setSuperAdmin(true);
        $administrator->setLocale('en');

        $userManager->updateUser($administrator, true);
        $this->addReference('user-administrator', $administrator);

        /** @var \AppBundle\Entity\User $defaultUser */
        $defaultUser = $userManager->createUser();
        $defaultUser->setUsername('user');
        $defaultUser->setEmail('user@symfony2-template.io');
        $defaultUser->setPlainPassword('test');
        $defaultUser->setEnabled(true);
        $defaultUser->setLocale('en');

        $userManager->updateUser($defaultUser, true);
        $this->addReference('user-default', $defaultUser);

        /** @var \AppBundle\Entity\User $frenchUser */
        $frenchUser = $userManager->createUser();
        $frenchUser->setUsername('user-french');
        $frenchUser->setEmail('user-french@symfony2-template.io');
        $frenchUser->setPlainPassword('test');
        $frenchUser->setEnabled(true);
        $frenchUser->setLocale('fr');
        $frenchUser->setConfirmationToken($tokenGenerator->generateToken());

        $userManager->updateUser($frenchUser, true);
        $this->addReference('user-french', $frenchUser);

        /** @var \AppBundle\Entity\User $germanUser */
        $germanUser = $userManager->createUser();
        $germanUser->setUsername('user-german');
        $germanUser->setEmail('user-german@symfony2-template.io');
        $germanUser->setPlainPassword('test');
        $germanUser->setEnabled(true);
        $germanUser->setLocale('de');
        $germanUser->setConfirmationToken($tokenGenerator->generateToken());

        $userManager->updateUser($germanUser, true);
        $this->addReference('user-german', $germanUser);

        /** @var \AppBundle\Entity\User $unconfirmedUser */
        $unconfirmedUser = $userManager->createUser();
        $unconfirmedUser->setUsername('user-unconfirmed');
        $unconfirmedUser->setEmail('user-unconfirmed@symfony2-template.io');
        $unconfirmedUser->setPlainPassword('test');
        $unconfirmedUser->setEnabled(false);
        $unconfirmedUser->setLocale('en');
        $unconfirmedUser->setConfirmationToken($tokenGenerator->generateToken());

        $userManager->updateUser($unconfirmedUser, true);
        $this->addReference('user-unconfirmed', $unconfirmedUser);

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
