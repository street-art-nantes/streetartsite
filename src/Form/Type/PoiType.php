<?php

namespace App\Form\Type;

use App\Entity\Poi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;

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
        $builder->add('latitude', NumberType::class, [
            'label' => 'artwork.label.latitude',
            'constraints' => [
                new Length(['min' => 3]),
                new GreaterThan(0),
                ],
        ]);
        $builder->add('longitude', NumberType::class, [
            'label' => 'artwork.label.longitude',
            'constraints' => [
                new Length(['min' => 3]),
                new GreaterThan(0),
            ],
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

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $poi = $event->getData();
            $alterDatas = [
                'latitude' => round(str_replace(',', '.', $poi['latitude']), 6),
                'longitude' => round(str_replace(',', '.', $poi['longitude']), 6),
            ];

            $event->setData(array_merge($poi, $alterDatas));
        });
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Poi::class);
    }
}
