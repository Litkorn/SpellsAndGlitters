<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class EditPassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('oldPassword', PasswordType::class, [
            'label'             => 'Mot de passe actuel',
            'mapped'            => false
        ])
        ->add('plainPassword', RepeatedType::class, [
            'type'              => PasswordType::class,
            'invalid_message'   => 'Les mots de passes ne correspondent pas',
            'first_options'     => ['label' => 'Nouveau mot de passe'],
            'second_options'    => ['label' => 'Confirmer le mot de passe'],
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'mapped'            => false,
            'attr'              => ['autocomplete' => 'new-password'],
            'constraints' => [
                new NotBlank([
                    'message' => 'le mot de passe est obligatoire',
                ]),
                new Length([
                    'min' => 8,
                    'minMessage' => 'Votre mot de passe doit faire au minimum {{ limit }} caractères',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
                new Regex([
                    'pattern' => '/^(?=.*[a-z])(?=.*\d)(?=.*[@$!%#*?&])[A-Za-z\d@$!%#*?&]{8,}$/',
                    'match' => true,
                    "message" => 'Votre mot de passe doit contenir au moins un chiffre, un caractère spécial (@$!#%*?&), une lettre minuscule et une lettre majuscule !'
                ])
            ]]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
