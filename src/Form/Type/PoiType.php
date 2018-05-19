<?php

namespace App\Form\Type;

use App\Entity\Poi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PoiType.
 */
class PoiType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('latitude', TextType::class, [
            'label' => 'artwork.label.latitude',
        ]);
        $builder->add('longitude', TextType::class, [
            'label' => 'artwork.label.longitude',
        ]);
        $builder->add('address', TextType::class, [
            'label' => 'artwork.label.address',
        ]);
        $builder->add('country', TextType::class, [
            'label' => 'artwork.label.country',
        ]);
        $builder->add('city', TextType::class, [
            'label' => 'artwork.label.city',
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Poi::class);
    }
}
