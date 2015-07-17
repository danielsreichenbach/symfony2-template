<?php

namespace spec\AppBundle\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class LocaleListenerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\EventListener\LocaleListener');
    }
}
