<?php
namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints\BookingDate;
use App\Validator\Constraints\AvailableSeats;


class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom complet : ',
                'attr' => ['class' => 'form-control custom-name-class',
                'placeholder' => 'Entrez votre nom complet',
            ],
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
            ->add('phone_number', TextType::class, [
                'label' => 'Numéro de téléphone : ',
                'attr' => ['class' => 'form-control custom-phone-class',
                'placeholder' => 'Entrez votre numéro de téléphone'],
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
            ->add('email', EmailType::class, [
                'label' => 'Email : ',
                'attr' => ['class' => 'form-control custom-email-class',
                'placeholder' => 'Entrez votre adresse email'],
            ])
            ->add('nb_guest', IntegerType::class, [
                'label' => 'Nombre d\'invités : ',
                'attr' => ['class' => 'form-control custom-guest-class',
                'placeholder' => 'Entrez le nombre d\'invités'],
                'constraints' => [
                        new Assert\NotBlank(['message' => 'Le nombre d\'invités ne doit pas être vide.']),
                        new Assert\GreaterThan([
                            'value' => 0,
                            'message' => 'Le nombre d\'invités doit être supérieur à 0.',
                        ]),
                        new Assert\LessThanOrEqual([
                            'value' => 60,
                            'message' => 'Le nombre d\'invités ne peut pas dépasser 60.',
                        ]),
                        new AvailableSeats(),
    ],
            ])
            ->add('date', DateTimeType::class, [
                'label' => 'Date et Heure : ',
                'widget' => 'single_text',
                
                'attr' => ['class' => 'form-control datetimepicker',
                'placeholder' => 'Entrez la date et l\'heure de la réservation'],
                'constraints' => [
                            new BookingDate(),
                        ],
            ])

            ->add('motif', ChoiceType::class, [
                'label' => 'Motif (facultatif) : ',
                'choices' => [
                    'Sélectionnez un motif' => '',
                    'Anniversaire' => 'Anniversaire',
                    'Réunion' => 'Réunion',
                    'Dîner romantique' => 'Dîner romantique',
                    'Événement d\'entreprise' => 'Événement d\'entreprise',
                    'Autre' => 'Autre',
                ],
                'attr' => ['class' => 'form-control custom-motif-class',],
                'required' => false,
                ])
            ->add('special_request', TextareaType::class, [
                'label' => 'Message (facultatif) : ',
                'required' => false,
                'attr' => ['class' => 'form-control custom-request-class',
                'placeholder' => 'Entrez votre demande spéciale'],
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z0-9\s.,?!]+$/',
                        'message' => 'Le message ne doit contenir que des lettres, des chiffres, des espaces, et les caractères .,?!',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}