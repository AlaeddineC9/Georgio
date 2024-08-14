<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Vich\UploaderBundle\Form\Type\VichImageType;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;


class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('id', 'ID')->onlyOnIndex(), // ID affiché uniquement dans l'index
            TextField::new('name', 'Nom de la Catégorie'),
            ImageField::new('photo')
            ->setBasePath('/uploads/categories')
            ->setLabel('Image Actuelle')
            ->onlyOnIndex(), 
            TextField::new('photoFile')
            ->setFormType(VichImageType::class)
            ->setLabel('Télécharger une nouvelle photo')
            ->onlyOnForms(), 
        ];
    }

}
