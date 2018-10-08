<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('city',
            null,
            [
                'label' => 'form.city',
                'translation_domain' => 'FOSUserBundle',
                'required' => false,
            ]
        );
        $builder->add('country',
            CountryType::class,
            [
                'label' => 'form.country',
                'translation_domain' => 'FOSUserBundle',
                'preferred_choices' => ['FR'],
                'required' => false,
            ]
        );
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
