<?php

namespace spec\AppBundle\Form\DataTransformer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Doctrine\ORM\EntityManager;

class InvitationToCodeTransformerSpec extends ObjectBehavior
{
    public function let(EntityManager $entityManager)
    {
        $this->beConstructedWith($entityManager);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Form\DataTransformer\InvitationToCodeTransformer');
    }

    public function it_implements_form_data_transformer_interface()
    {
        $this->shouldImplement('Symfony\Component\Form\DataTransformerInterface');
    }
}
