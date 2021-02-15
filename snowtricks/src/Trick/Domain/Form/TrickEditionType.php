<?php

namespace App\Trick\Domain\Form;

use App\Trick\Domain\Entity\Trick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class TrickEditionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,
                options: ['required' => true,])
            ->add('slug', TextType::class,
                options: ['required' => true,])
            ->add('description', TextareaType::class,
                options: ['required' => true,])
            ->add('image', FileType::class,
                options: [
                    'required' => true,
                    'label' => 'Photos.',
                    'mapped' => false,
                    'constraints' => [
                        new File([
                            'maxSize' => '5m',
                            'mimeTypes' => [
                                'image/png',
                                'image/jpg',
                                'image/gif',
                                'image/webp',
                            ],
                            'mimeTypesMessage' => 'Le type d\'images est incorrect',
                        ])
                    ],
                ])
            ->add('video', TextType::class,
                options: [
                    'required' => false,
                    'label' => 'Video.',
                    'mapped' => false,
                ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
