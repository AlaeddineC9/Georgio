<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
        if (!$category) {
            return new JsonResponse(['error' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

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

    #[Route('/admin/category/edit/{id}', name: 'admin_edit_category')]
    public function editCategory(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photoFile')->getData();

            if ($photoFile) {
                $category->setPhotoFile($photoFile);
            }

            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'Catégorie mise à jour avec succès.');
            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
        ]);
    }
}