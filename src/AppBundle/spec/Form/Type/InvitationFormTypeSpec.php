<?php

namespace spec\AppBundle\Form\Type;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use AppBundle\Form\DataTransformer\InvitationToCodeTransformer;

class InvitationFormTypeSpec extends ObjectBehavior
{
    public function let(InvitationToCodeTransformer $invitationToCodeTransformer)
    {
        $this->beConstructedWith($invitationToCodeTransformer);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Form\Type\InvitationFormType');
    }

    public function it_should_be_a_form_type()
    {
        $this->shouldImplement('Symfony\Component\Form\FormTypeInterface');
    }

    public function it_has_text_type_as_parent()
    {
        $this->getParent()->shouldReturn('text');
    }

    public function it_has_name()
    {
        $this->getName()->shouldReturn('app_invitation_type');
    }
}
