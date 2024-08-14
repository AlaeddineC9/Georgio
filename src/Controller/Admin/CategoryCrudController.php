<?php
namespace App\Controller\Admin;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;


class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            integerField::new('id', 'ID')->onlyOnIndex(),
            TextField::new('name', 'Nom de la Catégorie'),
            
            ImageField::new('image')
                ->setBasePath('/uploads/categories')
                ->setUploadDir('public/uploads/categories')
                ->setLabel('Image Actuelle')
                ->setRequired(false),

            
        ];
    }
    // public function configureActions(Actions $actions): Actions
    // {
    //     return $actions
    //         ->add(Crud::PAGE_INDEX, Action::DETAIL);
    // }
    // public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    // {
    //     if (!$entityInstance instanceof Category) {
    //         throw new \LogicException('L\'entité doit être de type Category.');
    //     }
    
    //     parent::deleteEntity($entityManager, $entityInstance);
    // }
}