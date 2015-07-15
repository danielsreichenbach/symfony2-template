<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides fixtures for testing groups and group permissions
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class LoadGroupData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        /**
         * @var \FOS\UserBundle\Model\GroupManagerInterface
         */
        $groupManager = $this->container->get('fos_user.group_manager');

        /** @var \AppBundle\Entity\Group $groupAdmin */
        $groupAdmin = $groupManager->createGroup('Staff');
        $groupAdmin->setRoles(array('ROLE_ADMIN'));
        $groupManager->updateGroup($groupAdmin, true);
        $this->addReference('group-admin', $groupAdmin);

        /** @var \AppBundle\Entity\Group $groupUser */
        $groupUser = $groupManager->createGroup('Users');
        $groupUser->setRoles(array('ROLE_USER'));
        $groupManager->updateGroup($groupUser, true);
        $this->addReference('group-user', $groupUser);

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 10;
    }
}
