<?php

namespace spec\AppBundle\Entity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class GroupSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('Test group');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Entity\Group');
    }

    public function its_name_is_mutable()
    {
        $this->setName('Group name test');
        $this->getName()->shouldReturn('Group name test');
    }
}
