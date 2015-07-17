<?php

namespace AppBundle\Repository;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityRepository;

/**
 * Provides generic helpers to access User entities
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class UserRepository extends EntityRepository
{
    /**
     * Find the first User
     *
     * @return User
     */
    public function findFirst()
    {
        $qb = $this->getQueryBuilder()
            ->orderBy('u.id', 'asc')
            ->setMaxResults(1);

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * Retrieve a limited list of enabled User entities.
     *
     * @param integer $amount
     *
     * @return array|\AppBundle\Entity\User[]
     */
    public function findEnabledUsers($amount = 50)
    {
        return $this->findBy(
            array('enabled' => 1),
            array('id' => 'DESC'),
            $amount
        );
    }

    /**
     * Retrieve a list of User entities pending confirmation
     *
     * @return array|\AppBundle\Entity\User[]
     */
    public function findUnconfirmedUsers()
    {
        $queryBuilder = $this->getQueryBuilder()
            ->where('u.enabled = 0')
            ->andWhere('u.confirmationToken IS NOT NULL');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Retrieve the latest registered, enabled users
     *
     * @param  integer $amount
     *
     * @return array|\AppBundle\Entity\User[]
     */
    public function findLatestUsers($amount)
    {
        $queryBuilder = $this->getQueryBuilder()
            ->orderBy('u.created', 'DESC')
            ->where('u.enabled = 1')
            ->setMaxResults($amount);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Fetch and return a query builder for User entity
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getQueryBuilder()
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->getRepository('AppBundle:User')
            ->createQueryBuilder('u');

        return $queryBuilder;
    }
}
