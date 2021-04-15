<?php

namespace App\Form;

use App\Entity\Activity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CancelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null,
                ['disabled' => true])
            ->add('beginDateTime', DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'disabled' => true
            ])
            ->add('campus',null,
                ['disabled' => true]
            )
            ->add('location',null,
                ['disabled' => true])
            ->add('cancellationReason')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);
    }
}
