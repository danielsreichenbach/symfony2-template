<?php

namespace spec\AppBundle\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserEventListenerSpec extends ObjectBehavior
{
    public function let(EntityManager $entityManager, ContainerInterface $container)
    {
        $this->beConstructedWith($entityManager, $container);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\EventListener\UserEventListener');
    }
}
