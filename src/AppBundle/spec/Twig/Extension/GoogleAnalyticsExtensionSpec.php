<?php

namespace spec\AppBundle\Twig\Extension;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class GoogleAnalyticsExtensionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Twig\Extension\GoogleAnalyticsExtension');
    }

    public function it_is_a_twig_extension()
    {
        $this->shouldHaveType('Twig_Extension');
    }
}
