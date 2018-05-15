<?php

namespace App\Form\Type;

use App\Entity\Artwork;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ArtworkType
 * @package App\Form\Type
 */
class ArtworkType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title');
        $builder->add('poi', PoiType::class, [
            'label' => false,
        ]);
        $builder->add('documents', CollectionType::class, [
            'entry_type' => DocumentType::class,
            'entry_options' => [],
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
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
