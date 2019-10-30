<?php

namespace App\Form\Type;

use FOS\UserBundle\Form\Type\ProfileFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

/**
 * Class ProfileType.
 */
class ProfileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
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
        $builder->add('language',
            ChoiceType::class,
            [
                'label' => 'form.language',
                'choices'  => [
                    'language.english' => 'en',
                    'language.french' => 'fr',
                ],
                'required' => false,
                'placeholder' => false,
            ]
        );
        $builder->add('website',
            UrlType::class,
            [
                'label' => 'form.website',
                'required' => false,
            ]
        );
        $builder->add('description',
            TextareaType::class,
            [
                'label' => 'form.description',
                'required' => false,
            ]
        );
        $builder->add('avatarFile', VichImageType::class, [
            'label' => 'form.avatar',
            'translation_domain' => 'messages',
            'allow_delete' => false,
            'required' => false,
        ]);
    }

    /**
     * @return string|null
     */
    public function getParent()
    {
        return ProfileFormType::class;
    }

    /**
     * @return string|null
     */
    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
