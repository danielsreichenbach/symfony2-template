<?php

namespace spec\AppBundle\Service;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Knp\Menu\FactoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MenuBuilderSpec extends ObjectBehavior
{
    public function let(FactoryInterface $knpFactory, AuthorizationCheckerInterface $authChecker, TokenStorageInterface $tokenStorage)
    {
        $this->beConstructedWith($knpFactory, $authChecker, $tokenStorage);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Service\MenuBuilder');
    }
}
