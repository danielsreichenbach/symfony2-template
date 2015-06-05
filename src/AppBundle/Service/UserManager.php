<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This class provides access to common user functionality and thus allows easier
 * reuse.
 *
 * Common use cases for this are:
 *
 *   * special User events
 *   * retrieving or updating specific User collections
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class UserManager
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Injecting the requirements
     *
     * @param EntityManager      $entityManager
     * @param ContainerInterface $containerInterface
     */
    public function __construct(EntityManager $entityManager, ContainerInterface $containerInterface)
    {
        $this->entityManager = $entityManager;
        $this->container     = $containerInterface;
    }

    /**
     * Adds the given user to the sites default group.
     *
     * @param User $user
     */
    public function addUserTodefaultGroup(User $user)
    {
        $defaultGroup = $this->container->getParameter('app.users.default_group');

        /** @var \AppBundle\Repository\GroupRepository $groupRepository */
        $groupRepository = $this->entityManager->getRepository('AppBundle:Group');
        /** @var \AppBundle\Entity\Group $group */
        $group = $groupRepository->findOneBy(array('name' => $defaultGroup));
        $user->addGroup($group);
        $this->entityManager->flush();
    }
}
