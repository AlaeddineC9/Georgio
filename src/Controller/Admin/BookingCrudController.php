<?php

namespace App\Controller\Admin;

use App\Entity\Booking;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class BookingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Booking::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom du client'),
            TextField::new('phone_number', 'Numéro de téléphone'),
            TextField::new('email', 'Email'),
            DateTimeField::new('date', 'Date de la réservation'),
            BooleanField::new('isAccepted', 'Acceptée'),
            BooleanField::new('isVerified', 'Vérifiée'),
        ];
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
    // public function configureActions(Actions $actions): Actions
    // {
    //     return $actions
    //         ->add(Crud::PAGE_INDEX, Action::EDIT)
    //         ->add(Crud::PAGE_INDEX, Action::DELETE);
    // }
}