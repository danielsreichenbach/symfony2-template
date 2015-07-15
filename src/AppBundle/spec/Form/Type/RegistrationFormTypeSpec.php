<?php

namespace spec\AppBundle\Form\Type;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use AppBundle\Entity\User;

class RegistrationFormTypeSpec extends ObjectBehavior
{
    public function let(User $user)
    {
        $this->beConstructedWith($user);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Form\Type\RegistrationFormType');
    }

    public function it_should_be_a_form_type()
    {
        $this->shouldImplement('Symfony\Component\Form\FormTypeInterface');
    }

    public function it_has_name()
    {
        $this->getName()->shouldReturn('app_user_registration');
    }
}
