<?php

namespace App\Controller;

use App\Repository\GalerieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; // Correction de l'import
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(GalerieRepository $galerieRepository): Response
    {
        // Récupérer toutes les images de la galerie
        $images = $galerieRepository->findAll();

        // Passer les images au template
        return $this->render('home/index.html.twig', [
            'title' => 'Auberge Georgio',
            'images' => $images,
        ]);
    }
}

