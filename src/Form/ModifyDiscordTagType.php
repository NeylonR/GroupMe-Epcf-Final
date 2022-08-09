<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ModifyDiscordTagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('discordTag', TextType::class, [
            'required' => false,
            'constraints' => [
                new Length([
                'max' => 40,
                'maxMessage' => 'Votre tag doit faire moins de {{ limit }} caractères',
                ]),
                new Regex([
                    'pattern' => '/^.{1,30}#[0-9]{4,4}$/',
                    'message' => 'Veuillez renseigner un tag discord valide.'
                ]),
                new NotBlank([
                    'message' => 'Ce champ ne peut pas être envoyé vide.'
                ])
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
