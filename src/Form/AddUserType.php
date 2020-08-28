<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom', 'attr' => ['placeholder' =>'Votre nom']])
            ->add('firstName', TextType::class, ['label' => 'Prénom', 'attr' => ['placeholder' =>'Votre prénom']])
            ->add('email', EmailType::class,['label' => 'Email','attr' => ['placeholder' =>'Votre émail']])
            ->add('password', PasswordType::class, ['label' => 'Mot de passe', 'attr' => ['placeholder' =>'Votre mot de passe']])
            ->add('confirmation', PasswordType::class, ['attr' => ['placeholder' =>'Confirmer votre mot de passe']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
