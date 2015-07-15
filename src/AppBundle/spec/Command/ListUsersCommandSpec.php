<?php

namespace spec\AppBundle\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ListUsersCommandSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Command\ListUsersCommand');
    }
}
