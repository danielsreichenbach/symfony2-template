<?php

namespace spec\AppBundle\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserControllerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Controller\UserController');
    }
}
