<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\Invitation;

/**
 * Provides fixtures for testing invitation codes for user entity creation
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class LoadInvitationData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $freeInvitation = new Invitation();
        $freeInvitation->setSent(true);

        $this->addReference('invitation-free', $freeInvitation);
        $manager->persist($freeInvitation);

        $limitedInvitation = new Invitation();
        $limitedInvitation->setEmail('limited@symfony2-template.io');
        $limitedInvitation->setSent(true);

        $this->addReference('invitation-limited', $limitedInvitation);
        $manager->persist($limitedInvitation);

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 40;
    }
}
