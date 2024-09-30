<?php
// src/Controller/MentionController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MentionController extends AbstractController
{
    #[Route('/mentions-legales', name: 'mentions_legales')]
    public function mentionsLegales(): Response
    {
        return $this->render('mentions/mention.html.twig');
    }
    #[Route('/protection-des-donnees', name: 'protection_donnees')]
    public function protectionDonnees(): Response
    {
        return $this->render('mentions/protection.html.twig');
    }
}