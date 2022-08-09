<?php

namespace App\Form;

use App\Entity\Day;
use App\Entity\Language;
use App\Entity\HomeWorld;
use App\Entity\GamingType;
use App\Entity\PlayerAdvertisement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PlayerAdvertisementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('homeWorld', EntityType::class, [
                'class' => HomeWorld::class,
                'choice_label' => 'label',
                'placeholder' => 'Serveur',
                'required' => true,
                'group_by' => 'dataCenter'
                ])
            ->add('language', EntityType::class, [
                'class' => Language::class,
                'choice_label' => 'label',
                'placeholder' => 'Langue',
                'expanded' => false,
                'multiple' => false
                ])
            ->add('playerType', EntityType::class, [
                'class' => GamingType::class,
                'choice_label' => 'label',
                'placeholder' => 'Type de joueur',
                'required' => true
                ])
            ->add('ilvl', IntegerType::class, [
                'label' => 'Ilvl minimum',
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
                'required' => true
            ])
            ->add('activityStart', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
                'required' => true
            ])
            ->add('activityEnd', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
                'required' => true
            ])
            ->add('bannerFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => false,
                'delete_label' => 'Supprimer l\'image',
                'asset_helper' => true,
                'image_uri' => false,
                'download_link' => false,
                'label' => 'Bannière de l\'annonce'
            ])
            ->add('textContent', TextareaType::class, [
                'attr' => ['rows' => 8],
            ])
            ->add('fflogLink', TextType::class, [
                'required' => false,
            ])
            ->add('ffxivLink', TextType::class, [
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'button buttonGeneric'],
                'label' => 'Créer mon annonce'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PlayerAdvertisement::class,
            'allow_extra_fields' => true,
        ]);
    }
}
