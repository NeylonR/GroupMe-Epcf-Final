<?php

namespace App\Form;

use App\Entity\Day;
use App\Entity\DataCenter;
use App\Entity\Language;
use App\Entity\HomeWorld;
use App\Entity\GamingType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('homeWorld', EntityType::class, [
            'class' => HomeWorld::class,
            'choice_label' => 'label',
            'label' => 'Serveur',
            'placeholder' => '--Selection--',
            'required' => false,
            'group_by' => 'dataCenter'
        ])
        ->add('dataCenter', EntityType::class, [
            'class' => DataCenter::class,
            'choice_label' => 'label',
            'label' => 'Centre de données',
            'placeholder' => '--Selection--',
            'required' => false,
            'group_by' => 'region'
        ])
        ->add('gamingType', EntityType::class, [
            'class' => GamingType::class,
            'choice_label' => 'label',
            'placeholder' => '--Selection--',
            'required' => false,
            ])
        ->add('language', EntityType::class, [
            'class' => Language::class,
            'choice_label' => 'label',
            'label' => 'Langue',
            'placeholder' => '--Selection--',
            'required' => false,
        ])
        ->add('ilvl', IntegerType::class, [
            'label' => 'Ilvl minimum',
            'required' => false,
            'attr' => [
                'min' => 500,
                'max' => 650,
            ],
        ])
        ->add('day', EntityType::class, [
            'class' => Day::class,
            'choice_label' => 'label',
            'expanded' => true,
            'multiple' => true,
            'required' => false,
        ])
        ->add('submit', SubmitType::class, [
            'attr' => ['class' => 'button buttonPlayer'],
            'label' => 'Chercher'
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'method' =>  'GET',
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
