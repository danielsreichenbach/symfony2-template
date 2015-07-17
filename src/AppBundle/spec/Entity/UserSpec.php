<?php

namespace spec\AppBundle\Entity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class UserSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Entity\User');
    }
}
