<?php

namespace App\Auth\Domain\Form;

use App\Auth\Domain\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class,
                options: ['required' => true,
                          'constraints' => [
//                              new UniqueEntity(fields:'username', message: 'Le pseudo doit contenir au moins 4 caractères.'),
                          ]])
            ->add('email', EmailType::class,
                options: ['required' => true,
                          'constraints' => [
//                              new UniqueEntity(fields:'email', message: 'Le pseudo doit contenir au moins 4 caractères.'),
                          ]])
            ->add('password', PasswordType::class,
                options: ['required' => true,
                          'constraints' => [
//                              new Length(['min' => 8], minMessage: 'Le mot de passe doit contenir au moins 8 caractères.'),
//                              new Regex(pattern: "/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{8,20}$/",
//                                  message: 'Le mot de passe doit contenir 3 type de caractères dont une majuscules un nombres et un spéciale.',
//                                  htmlPattern: "^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{8,20}$"),
                          ]])
            ->add('password', RepeatedType::class,
                options: [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être identique.',
                'options' => ['attr' => ['class' => 'input-txt password-field']],
                'required' => true,
                'constraints' => [
//                    new Length(['min' => 8], minMessage: 'Le mot de passe doit contenir au moins 8 caractères.')
                ],
                'first_options'  => ['label' => 'Mot de passe', 'attr' => ['class' => 'input-txt password-field','placeholder' => 'mot de passe robuste']],
                'second_options' => ['label' => 'Confirmation', 'attr' => ['class' => 'input-txt password-field','placeholder' => 'confirmer']],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
//            'data_class' => User::class,
        ]);
    }
}
