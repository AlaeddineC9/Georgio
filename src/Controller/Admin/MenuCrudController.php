<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

class MenuCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Menu::class;
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('composition'),
            NumberField::new('prix'),
            AssociationField::new('category', 'Catégorie')
                ->setRequired(true) // Rendre la sélection obligatoire si nécessaire
                ->setCrudController(CategoryCrudController::class),
        ];
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
