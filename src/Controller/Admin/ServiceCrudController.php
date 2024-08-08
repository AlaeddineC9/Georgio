<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ServiceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Service::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom du Service'),
            TextField::new('description', 'Description'),

            ImageField::new('photo')
                ->setBasePath('/uploads/services')
                ->setLabel('Photo actuelle')
                ->onlyOnIndex(), // Affiche l'image dans la liste

            TextField::new('photoFile')
                ->setFormType(VichImageType::class)
                ->setLabel('Télécharger une nouvelle photo')
                ->onlyOnForms(), // Pour l'upload dans les formulaires
        ];
    }
}