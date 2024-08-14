<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'category')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }


    // #[Route('/category/{id}/items', name: 'category_items')]
    // public function getCategoryItems(Category $category): JsonResponse
    // {
    //     $menuItems = $category->getMenu();
    //     $menuItemsArray = [];

    //     foreach ($menuItems as $menuItem) {
    //         $menuItemsArray[] = [
    //             'name' => $menuItem->getName(),
    //             'composition' => $menuItem->getComposition(),
    //             'prix' => $menuItem->getPrix(),
    //         ];
    //     }

    //     return new JsonResponse([
    //         'category' => [
    //             'name' => $category->getName(),
    //         ],
    //         'menu' => $menuItemsArray,
    //     ]);
    // }
}
