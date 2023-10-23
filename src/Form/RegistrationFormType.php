<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped'        => false,
                'constraints'   => [
                    new IsTrue([
                        'message' => '',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type'              => PasswordType::class,
                'invalid_message'   => 'Les mots de passes ne correspondent pas',
                'first_options'     => ['label' => 'Mot de passe'],
                'second_options'    => ['label' => 'Confirmer le mot de passe'],
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped'            => false,
                'attr'              => ['autocomplete' => 'new-password'],
                'constraints'       => [
                    new NotBlank([
                        'message'       => 'Le mot de passe est obligatoire',
                    ]),
                    new Length([
                        'min'           => 8,
                        'minMessage'    => 'Le mot de passe doit avoir au moins {{ limit }} caracteres',
                        // max length allowed by Symfony for security reasons
                        'max'           => 4096,
                    ]),
                    new Regex([
                        'pattern'       => '/^(?=.*[a-z])(?=.*\d)(?=.*[@$!%#*?&])[A-Za-z\d@$!%#*?&]{8,}$/',
                        'match'         => true,
                        'message'       => 'Votre mot de passe doit contenir au moins un chiffre, un caractere special, une lettre en majuscule et une minuscule !'
                    ])
                ]
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
