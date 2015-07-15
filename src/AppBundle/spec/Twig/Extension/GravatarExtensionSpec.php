<?php

namespace spec\AppBundle\Twig\Extension;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GravatarExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Twig\Extension\GravatarExtension');
    }

    public function it_is_a_twig_extension()
    {
        $this->shouldHaveType('Twig_Extension');
    }
}
