<?php

namespace App\Auth\Domain\Form;

use App\Auth\Domain\Dto\UpdatePasswordDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdatePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('old_password', PasswordType::class,
                options: ['required' => true])
            ->add('password', RepeatedType::class,
                options: [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les mots de passe doivent être identique.',
                    'options' => ['attr' => ['class' => 'input-txt password-field']],
                    'required' => true,
                    'first_options'  => ['label' => 'Mot de passe', 'attr' => ['class' => 'input-txt password-field','placeholder' => 'nouveau mot de passe']],
                    'second_options' => ['label' => 'Confirmation', 'attr' => ['class' => 'input-txt password-field','placeholder' => 'confirmer']],
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => UpdatePasswordDto::class,
            'csrf_protection' => true,
        ]);
    }
}
