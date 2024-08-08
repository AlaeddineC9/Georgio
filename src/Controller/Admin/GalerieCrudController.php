<?php

namespace App\Controller\Admin;

use App\Entity\Galerie;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class GalerieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Galerie::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('description', 'Description'),
            
            ImageField::new('photo')
                ->setBasePath('/uploads/galerie')
                ->setLabel('Photo actuelle')
                ->onlyOnIndex(), // Affiche l'image dans la liste

            // Utilisation du champ pour l'upload de fichiers avec VichImageType
            TextField::new('photoFile')
                ->setFormType(VichImageType::class)
                ->setLabel('Télécharger une nouvelle photo')
                ->onlyOnForms(), // Afficher uniquement dans les formulaires
        ];
    }
}