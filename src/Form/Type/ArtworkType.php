<?php

namespace App\Form\Type;

use App\Entity\Artwork;
use App\Entity\Author;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ArtworkType.
 */
class ArtworkType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => 'artwork.label.title',
            'required' => false,
        ]);
        $builder->add('type', ChoiceType::class, [
            'label' => 'artwork.label.type',
            'required' => true,
            'choices' => [
                'artwork.label.graffiti' => Artwork::TYPE_GRAFFITI,
                'artwork.label.sticking' => Artwork::TYPE_STICKING,
                'artwork.label.mosaic' => Artwork::TYPE_MOSAIC,
            ],
        ]);
        $builder->add('poi', PoiType::class, [
            'label' => false,
        ]);
        $builder->add('documents', CollectionType::class, [
            'entry_type' => DocumentType::class,
            'entry_options' => [
                'label' => false,
            ],
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'label' => 'artwork.label.documents',
        ]);
        $builder->add('author', EntityType::class, [
            'class' => Author::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('a')
                    ->orderBy('a.name', 'ASC');
            },
            'choice_label' => 'name',
            'required' => false,
            'label' => false,
            'placeholder' => 'artwork.placeholder.author',
            'multiple' => true,
            'expanded' => true,
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Artwork::class);
    }
}
