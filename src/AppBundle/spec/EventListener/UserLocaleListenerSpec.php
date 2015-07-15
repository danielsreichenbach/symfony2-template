<?php

namespace spec\AppBundle\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Session\Session;

class UserLocaleListenerSpec extends ObjectBehavior
{
    public function let(Session $session)
    {
        $this->beConstructedWith($session);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\EventListener\UserLocaleListener');
    }
}
