<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GalerieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(GalerieRepository $galerieRepository, Request $request, ManagerRegistry $doctrine, MailerInterface $mailer): Response
    {
        // Récupérer toutes les images de la galerie
        $images = $galerieRepository->findAll();


        $bookingRepository = $doctrine->getRepository(Booking::class);
        $booking = $bookingRepository->findOneBy([]);

        
        if (!$booking) {
            // Créer un objet Booking par défaut si nécessaire
            $booking = new Booking();
            $booking->setCanBook(true); // Assurer une valeur par défaut si aucun enregistrement trouvé
        }
        
        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si la date de réservation est dans le futur
            $currentDate = new \DateTime();
            if ($booking->getDate() <= $currentDate) {
                $this->addFlash('error', 'La date de réservation doit être dans le futur, vous devez refaire votre reservation .');
                return $this->redirectToRoute('home');
            }
            
            // Enregistrer la réservation dans la base de données
            $entityManager = $doctrine->getManager();
            $entityManager->persist($booking);
            $entityManager->flush();

            // Envoyer un email de confirmation au client
            $clientEmail = (new Email())
                ->from('noreply@aubergegeorgio.com') // Remplacez par votre adresse email d'envoi
                ->to($booking->getEmail()) // Email du client
                ->subject('Confirmation de votre réservation')
                ->html($this->renderView('emails/confirmation.html.twig', [
                    'booking' => $booking,
                ])
            );

            $mailer->send($clientEmail);
             // Ajouter un message flash de succès
            $this->addFlash('success', 'Votre réservation a été enregistrée avec succès. Un email de confirmation a été envoyé. ');

            // Envoyer un email de notification à l'administrateur
            $adminEmail = (new Email())
                ->from('noreply@aubergegeorgio.com')
                ->to('admin@aubergegeorgio.com') // Remplacez par l'adresse email de l'administrateur
                ->subject('Nouvelle Réservation')
                ->html($this->renderView('emails/admin_notification.html.twig', [
                    'booking' => $booking,
                ]));

            $mailer->send($adminEmail);

            // Rediriger vers la page d'accueil ou une autre page
            return $this->redirectToRoute('home');        }

        // Passer les images au template
        return $this->render('home/index.html.twig', [
            'title' => 'Auberge Georgio',
            'images' => $images,
            'booking' => $booking,
            'reservationForm' => $form->createView(),
        ]);
    }
}

