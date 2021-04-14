<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Campus;
use App\Entity\City;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('beginDateTime', DateTimeType::class, [
//                'date_widget' => 'single_text',
//                'time_widget' => 'single_text',
                'widget'=> 'single_text',
                'attr' => ['class' => 'datepicker'],
                'html5' => false
                ])
            ->add('registrationDeadline', DateTimeType::class, [
                'widget'=> 'single_text',
                'attr' => ['class' => 'datepicker'],
                'html5' => false
            ])
            ->add('maximumUserNumber')
            ->add('duration')
            ->add('detail')
            //->add('campus')
            ->add('campus',EntityType::class,[
                'class'=> Campus::class,
                'choice_label'=> 'name',
                'placeholder' => 'Sélectionner un campus'
            ])
            // Créer le lieu pour faire fonctionner les relations entre location et activity
            ->add('location', LocationType::class)
            // Je rajoute une checkbox qui n'est pas liée à mon entité Activity pour choisir le State de mon Activité
            ->add('publier', CheckboxType::class, [
                'label'    => 'Voulez vous directement publier cette sortie ?',
                'mapped'=>false,
                'required'=>false
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
            'attr'=> ['novalidate'=>'novalidate']
        ]);
    }
}
