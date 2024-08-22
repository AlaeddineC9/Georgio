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

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom complet : ',
                'attr' => ['class' => 'form-control custom-name-class',
                'placeholder' => 'Entrez votre nom complet'
            ],
            ])
            ->add('phone_number', TextType::class, [
                'label' => 'Numéro de téléphone : ',
                'attr' => ['class' => 'form-control custom-phone-class',
                'placeholder' => 'Entrez votre numéro de téléphone'],
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
            ])
            ->add('date', DateTimeType::class, [
                'label' => 'Date et Heure : ',
                'widget' => 'single_text',
                
                'attr' => ['class' => 'form-control datetimepicker',
                'placeholder' => 'Entrez la date et l\'heure de la réservation'],
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}