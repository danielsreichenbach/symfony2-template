<?php

namespace spec\AppBundle\Service;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MenuBuilderSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Service\MenuBuilder');
    }
}
