<?php

namespace AppBundle\Form\Type;

use AppBundle\Form\DataTransformer\InvitationToCodeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class InvitationFormType
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class InvitationFormType extends AbstractType
{
    /**
     * @var InvitationToCodeTransformer
     */
    protected $invitationTransformer;

    /**
     * Set up the Invitation form
     *
     * @param InvitationToCodeTransformer $invitationTransformer
     */
    public function __construct(InvitationToCodeTransformer $invitationTransformer)
    {
        $this->invitationTransformer = $invitationTransformer;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer($this->invitationTransformer, true);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'class'    => 'AppBundle\Entity\Invitation',
                'required' => true,
            )
        );
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_invitation_type';
    }
}
