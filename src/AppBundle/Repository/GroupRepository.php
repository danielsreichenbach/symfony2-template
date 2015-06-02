<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Provides generic helpers to access Group entities
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class GroupRepository extends EntityRepository
{
    /**
     * Fetch and return a query builder for Group entity
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getQueryBuilder()
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->getRepository('AppBundle:Group')
            ->createQueryBuilder('g');

        return $queryBuilder;
    }
}
