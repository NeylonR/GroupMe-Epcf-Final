<?php

namespace App\Form;

use App\Entity\Day;
use App\Entity\Language;
use App\Entity\DataCenter;
use App\Entity\GamingType;;
use App\Entity\RecruitAdvertisement;
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

class RecruitAdvertisementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rosterName', TextType::class, [
            ])
            ->add('dataCenter', EntityType::class, [
                'class' => DataCenter::class,
                'choice_label' => 'label',
                'placeholder' => 'Centre de données',
                'group_by' => 'region'
                ])
            ->add('language', EntityType::class, [
                'class' => Language::class,
                'choice_label' => 'label',
                'placeholder' => 'Langue',
                'expanded' => false,
                'multiple' => false
                ])
            ->add('rosterType', EntityType::class, [
                'class' => GamingType::class,
                'choice_label' => 'label',
                'placeholder' => 'Type de roster'
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
            ])
            ->add('activityStart', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
            ])
            ->add('activityEnd', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
            ])
            ->add('bannerFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => false,
                'delete_label' => 'Supprimer l\'image',
                'asset_helper' => true,
                'image_uri' => false,
                'download_link' => false,
                'label' => 'Bannière de l\'annonce',
            ])
            ->add('textContent', TextareaType::class, [
                'attr' => ['rows' => 8],
            ])
            ->add('fflogLink', TextType::class, [
                'required' => false,
            ])
            ->add('discordServerLink', TextType::class, [
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'button buttonRoster'],
                'label' => 'Créer mon annonce'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecruitAdvertisement::class,
            'allow_extra_fields' => true,
        ]);
    }
}
