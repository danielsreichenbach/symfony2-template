<?php

namespace spec\AppBundle\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OpcacheClearCommandSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Command\OpcacheClearCommand');
    }
}