<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ModifyNameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom d\'utilisateur',
                'constraints' => [
                    new Length([
                    'min' => 6,
                    'max' => 40,
                    'minMessage' => 'Votre nom d\'utilisateur doit avoir un minimum de {{ limit }} caractères',
                    'maxMessage' => 'Votre nom d\'utilisateur doit faire moins de {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'button buttonGeneric'],
                'label' => 'Modifier'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
