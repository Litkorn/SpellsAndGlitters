<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EditInfosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'     => 'Prénom',
                'required'  => false
            ])
            ->add('lastName', TextType::class, [
                'label'     => 'Nom  ',
                'required'  => false
            ])
            ->add('email', EmailType::class, [
                'label'     => 'Nom  ',
                'required'  => false
            ])
            ->add('pass', PasswordType::class, [
                'label'     => 'Mot de passe',
                'mapped'    => false
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
