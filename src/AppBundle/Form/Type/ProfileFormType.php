<?php

namespace AppBundle\Form\Type;

use FOS\UserBundle\Form\Type\ProfileFormType as BaseProfileFormType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Extends profile form with locale support
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 */
class ProfileFormType extends BaseProfileFormType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->remove('username')
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
            );
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'fos_user_profile';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_user_profile';
    }
}
