<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['placeholder' => 'Nom d\'utilisateur'],
                'label' => false,
                'help' => '4 à 41 caractères, peut contenir un espace, caractère spéciaux interdit.'
            ])
            ->add('email', EmailType::class, [
                'attr' => ['placeholder' => 'Email'],
                'label' => false
            ])
            // ->add('agreeTerms', CheckboxType::class, [
            //     'mapped' => false,
            //     'constraints' => [
            //         new IsTrue([
            //             'message' => 'You should agree to our terms.',
            //         ]),
            //     ],
            // ])
            // ->add('plainPassword', PasswordType::class, [
            //     // instead of being set onto the object directly,
            //     // this is read and encoded in the controller
            //     'mapped' => false,
            //     'attr' => ['autocomplete' => 'Mot de passe', 'placeholder' => 'Mot de passe (6-60 caractères)'],
            //     'label' => false,
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Veuillez renseigner un mot de passe',
            //         ]),
            //         new Length([
            //             'min' => 6,
            //             'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractères',
            //             'max' => 60,
            //             'maxMessage' => 'Votre mot de passe doit faire moins de {{ limit }} caractères',
            //         ]),
            //     ],
            // ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passes ne correspondent pas.',
                'required' => true,
                'first_options'  => [
                    'label' => false,
                    'attr' => ['placeholder' => 'Mot de passe (6-60 caractères)'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez renseigner un mot de passe',
                        ]),
                    ],
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => ['placeholder' => 'Confirmer le mot de passe'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez renseigner un mot de passe',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Votre mot de passe doit avoir un minimum de {{ limit }} caractères',
                            'max' => 60,
                            'maxMessage' => 'Votre mot de passe doit faire moins de {{ limit }} caractères',
                        ]),
                    ],
                ],
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
