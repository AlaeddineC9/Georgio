<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


class BookingController extends AbstractController
{
    #[Route('/reservation', name: 'reservation')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer un enregistrement Booking, ou en créer un s'il n'en existe aucun
        $bookingRepository = $entityManager->getRepository(Booking::class);
        $booking = $bookingRepository->findOneBy([]) ?? new Booking();
    
        if (!$booking->canBook()) {
            $this->addFlash('error', 'Les réservations sont actuellement désactivées.');
            return $this->redirectToRoute('home');
        }
    
        $form = $this->createForm(BookingType::class, $booking);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($booking);
            $entityManager->flush();
    
            $this->addFlash('success', 'Votre réservation a été enregistrée avec succès.');
    
            return $this->redirectToRoute('home');
        }
    
        return $this->render('home/index.html.twig', [
            'reservationForm' => $form->createView(),
        ]);
    }
}
