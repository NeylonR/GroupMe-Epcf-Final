<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ModifyEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                // 'constraints' => [
                //     new Length([
                //     'min' => 6,
                //     'max' => 40,
                //     'minMessage' => 'Votre mot de passe doit avoir un minimum de {{ limit }} caractères',
                //     'maxMessage' => 'Votre mot de passe doit faire moins de {{ limit }} caractères',
                //     ]),
                //     new Email([
                //         'message' => 'Veuillez rentrer une adresse email valide.'
                //     ])
                // ],
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
