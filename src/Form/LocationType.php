<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',null,['label'=>'Nom'])
            ->add('road',null,['label'=>'Rue'])
            ->add('latitude',null,['label'=>'Lattitude','attr' => array(
                'placeholder' => 'Ce champs est optionnel',
            )])
            ->add('longitude',null,['attr' => array(
        'placeholder' => 'Ce champs est optionnel',
    )])
            ->add('city',null,['label'=>'Ville'])
            ->add('active',HiddenType::class,['data'=>true]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
