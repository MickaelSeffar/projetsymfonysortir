<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;

class SearchActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        $builder
                ->add('campusName',EntityType::class,[
                    'class'=> Campus::class,
                    'mapped'=>false,
                    'choice_label'=> 'name',
                    'required' => false,
                    'label'=> 'Campus'
                ])
            ->add('activityName', SearchType::class,[
                'label'    => "Le nom de la sortie contient",
                'mapped'=>false,
                'required'=>false
            ])
            ->add('managerB', CheckboxType::class, [
                'label'    => "Sorties dont je suis l'organisateur/trice",
                'mapped'=>false,
                'required'=>false
            ])
            ->add('registered', CheckboxType::class, [
                'label'    => "Sorties auxquelles je suis inscrit/e",
                'mapped'=>false,
                'required'=>false
            ])
            ->add('registeredActivity', CheckboxType::class, [
                'label'    => "Sorties auxquelles je ne suis pas inscrit/e",
                'mapped'=>false,
                'required'=>false
            ])
            ->add('finishActivity', CheckboxType::class, [
                'label'    => "Sorties passées",
                'mapped'=>false,
                'required'=>false
            ])
            ->add('startDate', DateTimeType::class, [
                'widget'=> 'single_text',
                'attr' => ['class' => 'datepicker'],
                'html5' => false,
                'mapped'=>false,
                'label'    => "Entre",


            ])
            ->add('endDate', DateTimeType::class, [
                'widget'=> 'single_text',
                'attr' => ['class' => 'datepicker'],
                'html5' => false,
                'mapped'=>false,
                'label'    => "et",
                'constraints' => [
                    new GreaterThan(['propertyPath'=> 'parent.all[startDate].data',
                        'message' => 'Cette date doit être supérieur à la première date'
                    ])
                ]

        ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);
    }
}
