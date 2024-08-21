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
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;


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

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

}
