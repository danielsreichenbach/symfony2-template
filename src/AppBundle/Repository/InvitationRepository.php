<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Provides generic helpers to access Invitation entities
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class InvitationRepository extends EntityRepository
{
    /**
     * Fetch and return a query builder for Group entity
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getQueryBuilder()
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->getRepository('AppBundle:Invitation')
            ->createQueryBuilder('i');

        return $queryBuilder;
    }
}
