<?php

namespace spec\AppBundle\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserLocaleListenerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\EventListener\UserLocaleListener');
    }
}
