<?php

namespace AppBundle\Form\Type;

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseRegistrationFormType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Invitation based user registration form
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class RegistrationFormType extends BaseRegistrationFormType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add(
                'locale',
                'choice',
                array(
                    'choices' => array(
                        'en' => 'en',
                        'de' => 'de',
                        'fr' => 'fr',
                    ),
                    'choices_as_values' => true,
                    'choice_label' => function ($allChoices, $currentChoiceKey) {
                        return 'app.locale.'.$currentChoiceKey;
                    },
                    'label' => 'form.locale',
                    'translation_domain' => 'FOSUserBundle',
                )
            )
            ->add(
                'invitation',
                'app_invitation_type',
                array(
                    'required' => true,
                    'label' => 'form.invitation',
                    'translation_domain' => 'FOSUserBundle',
                )
            );
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'fos_user_registration';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_user_registration';
    }
}
