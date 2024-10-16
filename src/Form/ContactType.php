<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints as Assert;


class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Votre nom',
                'attr' => ['placeholder' => 'Entrez votre nom',
                'class' => 'form-control custom-name-class',
            'id'=> 'name-input'],
                'constraints' => [
                new Assert\NotBlank(['message' => 'Le nom ne doit pas être vide.']),
                new Assert\Length([ 
                    'min' => 2,
                    'max' => 80,
                    'minMessage' => 'Le nom doit comporter au moins {{ limit }} caractères.',
                    'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.',
                ]),
                new Assert\Regex([
                    'pattern' => '/^[a-zA-Z0-9\s]+$/',
                    'message' => 'Le nom ne doit contenir que des lettres, des chiffres et des espaces.',
                ]),
            ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre email',
                'attr' => ['placeholder' => 'Entrez votre email',
                'class' => 'form-control custom-email-class',
            'id'=> 'email-input'],
            ])
            ->add('phone_number', TextType::class, [
                'label' => 'Votre numéro de téléphone',
                'attr' => ['placeholder' => 'Entrez votre numéro de téléphone',
                'class' => 'form-control custom-phone-class',
            'id'=> 'phone-input'],
                'constraints' => [
                new Assert\NotBlank(['message' => 'Le numéro de téléphone ne doit pas être vide.']),
                new Assert\Length([
                    'min' => 10,
                    'minMessage' => 'Le numéro de téléphone doit comporter au moins {{ limit }} caractères.',
                ]),
                new Assert\Regex([
            'pattern' => '/^[0-9\s\+\-]+$/',
            'message' => 'Le numéro de téléphone ne doit contenir que des chiffres, des espaces et les caractères "+" ou "-".',
                ]),
        ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Votre message',
                'attr' => ['placeholder' => 'Entrez votre message',
                'class' => 'form-control custom-message-class',
            'id'=> 'message-input'],
                'constraints' => [
                new Assert\NotBlank(['message' => 'Le message ne doit pas être vide.']),
                new Assert\Length([ 
                    'min' => 3,
                    'max' => 500,
                    'minMessage' => 'Le message doit comporter au moins {{ limit }} caractères.',
                    'maxMessage' => 'Le message ne peut pas dépasser {{ limit }} caractères.',
                ]),
                new Assert\Regex([
                    'pattern' => '/^[a-zA-Z0-9\s.,!?\'"()-]*$/u',
                    'message' => 'Le message contient des caractères non autorisés.',
                ]),

            ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}