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
    public function index(Request $request): Response
    {
        $booking = new Booking();
        if (!$booking->canBook()) {
            $this->addFlash('error', 'Les réservations sont actuellement désactivées.');
            return $this->redirectToRoute('home');
        }
        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // $currentDate = new \DateTime();
            // if ($booking->getDate() <= $currentDate) {
            //     $this->addFlash('error', 'La date de réservation doit être dans le futur.');
            //     return $this->redirectToRoute('reservation');
            // }


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($booking);
            $entityManager->flush();

            $this->addFlash('success', 'Votre réservation a été enregistrée avec succès.');

            return $this->redirectToRoute('home'); // Redirigez vers la page souhaitée
        }

        return $this->render('home/index.html.twig', [
            'reservationForm' => $form->createView(),
        ]);
    }
}
