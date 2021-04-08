<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\City;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
//            ->add('beginDateTime', date('Y-m-d H:i:s'), ['widget' => 'single_text'])
            ->add('beginDateTime', DateType::class, ['widget' => 'single_text'])
            ->add('registrationDeadline', DateType::class, ['widget' => 'single_text'])
            ->add('maximumUserNumber')
            ->add('duration')
            ->add('detail')
            ->add('campus')
            // Créer le lieu pour faire fonctionner les relations entre location et activity
            ->add('location', LocationType::class)
//            ->add('city', CityType::class, ['data' => City::class])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);
    }
}
