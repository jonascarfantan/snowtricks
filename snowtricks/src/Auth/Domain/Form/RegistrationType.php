<?php

namespace App\Auth\Domain\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class,
                options: ['required' => true,
                          'constraints' => [ new Length(['min' => 4], minMessage: 'Le pseudo doit contenir au moins 4 caractères.')
                          ]])
            ->add('email', EmailType::class,
                options: ['required' => true,
                          'constraints' => [ new Email(message: 'l\'Adresse doit être un email valide.')
                          ]])
            ->add('password', PasswordType::class,
                options: ['required' => true,
                          'constraints' => [
                              new Length(['min' => 8], minMessage: 'Le mot de passe doit contenir au moins 8 caractères.'),
//                              new EqualTo('confirm_password', message: '')
                          ]])
            ->add('password', RepeatedType::class,
                options: [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être identique.',
                'options' => ['attr' => ['class' => 'input-txt password-field']],
                'required' => true,
                'constraints' => [
                    new Length(['min' => 8], minMessage: 'Le mot de passe doit contenir au moins 8 caractères.')
                ],
                'first_options'  => ['label' => 'Mot de passe', 'attr' => ['class' => 'input-txt password-field','placeholder' => 'mot de passe robuste'],],
                'second_options' => ['label' => 'Confirmation', 'attr' => ['class' => 'input-txt password-field','placeholder' => 'confirmer'],],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
