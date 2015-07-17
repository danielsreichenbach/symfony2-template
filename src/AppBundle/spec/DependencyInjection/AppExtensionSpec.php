<?php

namespace spec\AppBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class AppExtensionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\DependencyInjection\AppExtension');
    }
}
