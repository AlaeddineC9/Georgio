<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'category')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('category/index.html.twig', [
            'title' => 'Menu',
            'categories' => $categories,

        ]);
    }
    #[Route('/category/{id}/menus', name: 'category_menus')]
    public function getCategoryMenus(Category $category): JsonResponse
    {
        
        $menus = $category->getMenu();
        $menusArray = [];

        foreach ($menus as $menu) {
            $menusArray[] = [
                'name' => $menu->getName(),
                'composition' => $menu->getComposition(),
                'prix' => $menu->getPrix(),
            ];
        }

        return new JsonResponse([
            'category' => $category->getName(),
            'menus' => $menusArray,
        ]);
    }
}
