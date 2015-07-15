<?php

namespace spec\AppBundle\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserEventListenerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\EventListener\UserEventListener');
    }
}
