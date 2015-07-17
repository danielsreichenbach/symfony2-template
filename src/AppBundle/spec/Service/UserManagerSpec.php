<?php

namespace spec\AppBundle\Service;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class UserManagerSpec extends ObjectBehavior
{
    public function let(EntityManager $entityManager, ContainerInterface $containerInterface)
    {
        $this->beConstructedWith($entityManager, $containerInterface);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Service\UserManager');
    }
}
