<?php

namespace AppBundle\Service;

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
}
