<?php

namespace spec\AppBundle\Repository;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class UserRepositorySpec extends ObjectBehavior
{
    public function let(EntityManager $em, ClassMetadata $classMetadata)
    {
        $this->beConstructedWith($em, $classMetadata);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Repository\UserRepository');
    }
}
