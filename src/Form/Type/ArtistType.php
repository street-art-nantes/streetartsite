<?php

namespace App\Form\Type;

use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Vich\UploaderBundle\Form\Type\VichImageType;

/**
 * Class ArtworkType.
 */
class ArtistType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'artist.label.name',
            'required' => true,
            'constraints' => [
                new Length(['min' => 2]),
            ],
        ]);
        $builder->add('biography', TextareaType::class, [
            'label' => 'artist.label.biography',
            'required' => false,
        ]);
        $builder->add('biographyEn', TextareaType::class, [
            'label' => 'artist.label.biographyEn',
            'required' => true,
            'constraints' => [
                new Length(['min' => 50]),
            ],
        ]);
        $builder->add('websiteLink', TextType::class, [
            'label' => 'artist.label.websiteLink',
            'required' => false,
        ]);
        $builder->add('instagramLink', TextType::class, [
            'label' => 'artist.label.instagramLink',
            'required' => false,
        ]);
        $builder->add('avatarFile', VichImageType::class, [
            'label' => 'artist.label.avatarFile',
            'translation_domain' => 'messages',
            'allow_delete' => false,
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Author::class);
    }
}
