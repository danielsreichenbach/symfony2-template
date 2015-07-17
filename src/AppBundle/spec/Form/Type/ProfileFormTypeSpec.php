<?php

namespace spec\AppBundle\Form\Type;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use AppBundle\Entity\User;

/**
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class ProfileFormTypeSpec extends ObjectBehavior
{
    public function let(User $user)
    {
        $this->beConstructedWith($user);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Form\Type\ProfileFormType');
    }

    public function it_should_be_a_form_type()
    {
        $this->shouldImplement('Symfony\Component\Form\FormTypeInterface');
    }

    public function it_has_fos_user_profile_type_as_parent()
    {
        $this->getParent()->shouldReturn('fos_user_profile');
    }

    public function it_has_name()
    {
        $this->getName()->shouldReturn('app_user_profile');
    }
}
