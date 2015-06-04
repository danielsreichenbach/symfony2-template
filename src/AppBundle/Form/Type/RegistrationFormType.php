<?php

namespace AppBundle\Form\Type;

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseRegistrationFormType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Invitation based user registration form
 *
 * @package AppBundle\Form\Type
 */
class RegistrationFormType extends BaseRegistrationFormType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('invitation', 'app_invitation_type', array('label' => 'form.invitation', 'translation_domain' => 'FOSUserBundle'));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_user_registration';
    }
}
