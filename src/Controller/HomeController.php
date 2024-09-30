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
        $bookingStatus = $bookingRepository->findOneBy([]);

        if (!$bookingStatus) {
            $bookingStatus = new Booking();
            $bookingStatus->setCanBook(true); // Valeur par défaut si aucun enregistrement trouvé
        }
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          // Conversion de la chaîne de caractères en DateTime si nécessaire
            $date = $booking->getDate();
            if (is_string($date)) {
                $booking->setDate(new \DateTime($date));
            }
            $date = $booking->getDate();
            if (!$date instanceof \DateTimeInterface) {
                $this->addFlash('error', 'La date de réservation est invalide.');
                return $this->redirectToRoute('home');
            }

            // Vérifier si nb_guest est > 0
            if ($booking->getNbGuest() <= 0) {
                $this->addFlash('error', 'Le nombre d\'invités doit être supérieur à 0.');
                return $this->redirectToRoute('home');
            }

            // Vérifier si la date de réservation est dans le futur
            $currentDate = new \DateTime();
            if ($booking->getDate() <= $currentDate) {
                $this->addFlash('error', 'La date de réservation doit être dans le futur.');
                return $this->redirectToRoute('home');
            }

            // Vérifier si l'heure de réservation est dans les créneaux autorisés
            $time = $booking->getDate()->format('H:i');

            $morningStart = '12:00';
            $morningEnd = '15:00';
            $eveningStart = '19:30';
            $eveningEnd = '22:30';

            if (
                ($time < $morningStart || $time > $morningEnd) &&
                ($time < $eveningStart || $time > $eveningEnd)
            ) {
                $this->addFlash('error', 'Les réservations ne sont autorisées que de 12h00 à 15h00 ou de 19h30 à 22h30.');
                return $this->redirectToRoute('home');
            }

            // Comparer les dates pour la même journée
            $startOfDay = (clone $booking->getDate())->setTime(0, 0);
            $endOfDay = (clone $booking->getDate())->setTime(23, 59, 59);

            // Trouver les réservations existantes pour la même journée
            $existingBookings = $bookingRepository->createQueryBuilder('b')
                ->where('b.date >= :startOfDay')
                ->andWhere('b.date <= :endOfDay')
                ->setParameter('startOfDay', $startOfDay)
                ->setParameter('endOfDay', $endOfDay)
                ->getQuery()
                ->getResult();

            // Calculer le nombre total d'invités pour chaque créneau spécifique
            $morningGuests = 0;
            $eveningGuests = 0;
            foreach ($existingBookings as $existingBooking) {
                $bookingTime = $existingBooking->getDate()->format('H:i');
                if ($bookingTime >= $morningStart && $bookingTime <= $morningEnd) {
                    $morningGuests += $existingBooking->getNbGuest();
                } elseif ($bookingTime >= $eveningStart && $bookingTime <= $eveningEnd) {
                    $eveningGuests += $existingBooking->getNbGuest();
                }
            }

            // Vérifier si la limite est atteinte pour le créneau spécifique
            if (($time >= $morningStart && $time <= $morningEnd && $morningGuests + $booking->getNbGuest() > 60) ||
                ($time >= $eveningStart && $time <= $eveningEnd && $eveningGuests + $booking->getNbGuest() > 60)) {
                $this->addFlash('error', 'Le nombre total de réservations pour ce créneau horaire a atteint sa limite de 60 personnes.');
                return $this->redirectToRoute('home');
            }
            // Enregistrer la réservation dans la base de données
            $entityManager = $doctrine->getManager();
            $entityManager->persist($booking);
            $entityManager->flush();

            // Envoyer un email de confirmation au client
        $clientEmail = (new Email())
    ->from('restaurant@aubergegeorgio.fr') // Remplacez par votre adresse email d'envoi
    ->to($booking->getEmail()) // Email du client
    ->subject('Confirmation de votre réservation')
    ->html(
        $this->renderView(
            'emails/confirmation.html.twig', [
                'booking' => $booking,
            ]
        )
    );

// Envoi de l'email
$mailer->send($clientEmail);

             // Ajouter un message flash de succès
            $this->addFlash('success', 'Votre réservation a été enregistrée avec succès. Un email de confirmation a été envoyé. ');

            // Envoyer un email de notification à l'administrateur
            $adminEmail = (new Email())
                ->from('restaurant@aubergegeorgio.fr')
                ->to('alaeddinechraiti@gmail.com') // Remplacez par l'adresse email de l'administrateur
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
            'bookingStatus' => $bookingStatus, // Passer l'état général des réservations à la vue

        ]);
    }
}

