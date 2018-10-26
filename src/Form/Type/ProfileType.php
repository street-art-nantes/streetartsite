<?php

namespace App\Form\Type;

use FOS\UserBundle\Form\Type\ProfileFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

/**
 * Class ProfileType
 * @package App\Form\Type
 */
class ProfileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
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
                'placeholder' => false,
            ]
        );
        $builder->add('avatarFile', VichImageType::class, [
            'label' => false,
            'translation_domain' => 'messages',
            'allow_delete' => false,
            'required' => false,
        ]);
    }

    /**
     * @return null|string
     */
    public function getParent()
    {
        return ProfileFormType::class;
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
