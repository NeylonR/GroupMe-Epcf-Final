<?php

namespace App\Form;

use App\Entity\Job;
use App\Entity\Role;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;

class JobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'label' => 'Nom du job'
            ])
            ->add('role', EntityType::class, [
                'class' => Role::class,
                'choice_label' => 'label',
                'label' => 'Role du job'
            ])
            ->add('iconFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer l\'image',
                'asset_helper' => true,
                'image_uri' => false,
                'download_link' => false,
                'label' => 'Icône du job'
            ])
            // ->add('label', LanguageType::class, [
            //     'choice_translation_locale' => 'fr',
            //     'expanded' => true,
            //     'multiple' => true,
            // ])
            ->add('Ajouter', SubmitType::class, [
                'attr' => ['class' => 'button buttonGeneric']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Job::class,
            'attr' => ['class' => 'adminForm']
        ]);
    }
}
