<?php

namespace spec\AppBundle\Repository;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;

class InvitationRepositorySpec extends ObjectBehavior
{
    public function let(EntityManager $em, ClassMetadata $classMetadata)
    {
        $this->beConstructedWith($em, $classMetadata);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Repository\InvitationRepository');
    }
}
