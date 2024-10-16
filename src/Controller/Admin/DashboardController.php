<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MailjetService;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Booking;
use App\Entity\Galerie;
use App\Entity\Contact;
use App\Entity\Service;
use App\Entity\Category;
use App\Entity\Menu;
use App\Entity\Client;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends AbstractDashboardController
{

    private $entityManager;
    private $mailjetService;

    public function __construct(EntityManagerInterface $entityManager , MailjetService $mailjetService)
    {
        $this->entityManager = $entityManager;
        $this->mailjetService = $mailjetService;
    }

    
    
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Vérifiez s'il y a des bookings dans la base de données
        $bookings = $this->getPendingBookings($this->entityManager);
    
        if (empty($bookings)) {
            // Si aucune réservation n'existe, créez une réservation de test
            $testBooking = new Booking();
            $testBooking->setName('Test Client')
                        ->setPhoneNumber('1234567890')
                        ->setEmail('test@example.com')
                        ->setNbGuest(2)
                        ->setDate(new \DateTime('+1 day')) // Date de demain
                        ->setIsVerified(false)
                        ->setCanBook(true);
    
            $this->entityManager->persist($testBooking);
            $this->entityManager->flush();
    
            // Rechargez les bookings après avoir inséré la réservation de test
            $bookings = $this->getPendingBookings($this->entityManager);
        }
    
        $bookingStatus = $this->getBookingStatus($this->entityManager);
    
        return $this->render('admin/dashboard.html.twig', [
            'bookings' => $bookings,
            'bookingStatus' => $bookingStatus,
        ]);
    }

    private function getPendingBookings(EntityManagerInterface $entityManager): array
    {
        // Utilisation de l'EntityManager pour récupérer les réservations en attente
        // return $this->entityManager->getRepository(Booking::class)->findAll();
        return $entityManager->getRepository(Booking::class)
            ->createQueryBuilder('b')
            ->orderBy('b.isVerified', 'ASC') // Trier par date en ordre décroissant
            ->getQuery()
            ->getResult();
    }

    private function getBookingStatus(EntityManagerInterface $entityManager): ?Booking
{
    // Récupérer l'enregistrement spécifique pour l'activation
    return $entityManager->getRepository(Booking::class)->findOneBy([]);
}

#[Route('/admin/bookings', name: 'admin_bookings')]
public function listBookings(EntityManagerInterface $entityManager): Response
{
    $bookings = $entityManager->getRepository(Booking::class)->findAll();

    foreach ($bookings as $booking) {
        if ($booking->isVerified() === null) {
            $booking->setIsVerified(true); // Marquer comme vue
        }
    }
    $entityManager->flush();

    return $this->render('admin/bookings.html.twig', [
        'bookings' => $bookings,
    ]);
}






    #[Route('/admin/confirm-booking/{id}', name: 'admin_confirm_booking')]
    public function confirmBooking(Request $request, Booking $booking): Response
    {
        $booking->setIsVerified(true);
        $this->entityManager->flush();

        // Générer le contenu HTML de l'email
        $htmlContent = $this->renderView('emails/booking_confirmation.html.twig', [
            'booking' => $booking,
        ]);

        try {
            // Envoyer l'email de confirmation au client
            $this->mailjetService->sendEmail(
                $booking->getEmail(),
                $booking->getName(),
                'Confirmation de votre réservation',
                $htmlContent
            );

            $this->addFlash('success', 'La réservation a été confirmée avec succès. Un email de confirmation a été envoyé.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi de l\'email : ' . $e->getMessage());
        }

        return $this->redirectToRoute('admin');
    }

    #[Route('/admin/deny-booking/{id}', name: 'admin_deny_booking')]
    public function denyBooking(Request $request, Booking $booking): Response
    {
        $booking->setIsVerified(false);
        $this->entityManager->flush();

        // Générer le contenu HTML de l'email
        $htmlContent = $this->renderView('emails/booking_denied.html.twig', [
            'booking' => $booking,
        ]);

        try {
            // Envoyer l'email de refus au client
            $this->mailjetService->sendEmail(
                $booking->getEmail(),
                $booking->getName(),
                'Réservation refusée',
                $htmlContent
            );

            $this->addFlash('success', 'La réservation a été refusée avec succès. Un email de notification a été envoyé.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi de l\'email : ' . $e->getMessage());
        }

        return $this->redirectToRoute('admin');
    }

    #[Route('/admin/toggle-booking-status', name: 'toggle_booking_status')]
    public function toggleBookingStatus(EntityManagerInterface $entityManager): Response
    {
        $bookingRepository = $entityManager->getRepository(Booking::class);
    
        // Récupérer un enregistrement Booking, ou en créer un s'il n'en existe aucun
        $booking = $bookingRepository->findOneBy([]);
    
        if ($booking) {
            // Inverse la valeur de canBook
            $booking->setCanBook(!$booking->canBook());
            $entityManager->flush();
    
            $this->addFlash('success', 'Le statut de réservation a été mis à jour avec succès.');
        } else {
            // Crée un nouveau Booking avec la valeur par défaut pour canBook
            $newBooking = new Booking();
            $newBooking->setCanBook(true); // Ou false, selon votre besoin
    
            $entityManager->persist($newBooking);
            $entityManager->flush();
    
            $this->addFlash('success', 'Un nouvel enregistrement de réservation a été créé.');
        }
    
        return $this->redirectToRoute('admin');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Georgio');
    }

    public function configureMenuItems(): iterable
    {
        $newContactsCount = $this->getNewContactsCount();
        $newBookingsCount = $this->getNewBookingsCount();
        yield MenuItem::linkToDashboard('Acceuil', 'fa fa-home');
        // yield MenuItem::linkToCrud('client', 'fa fa-user', Client::class);
        yield MenuItem::linkToCrud('galerie', 'fa fa-image', Galerie::class);
        
        yield MenuItem::linkToCrud(
            sprintf('Contacts %s', $newContactsCount > 0 ? '<span class="badge badge-danger">' . $newContactsCount . '</span>' : ''),
            'fa fa-envelope',
            Contact::class
        );
        
        
        yield MenuItem::linkToCrud(
            'Reservations ' . ($newBookingsCount > 0 ? sprintf('<span class="badge badge-danger">%d</span>', $newBookingsCount) : ''),
            'fa fa-bed',
            Booking::class
        );        yield MenuItem::linkToCrud('services', 'fas fa-tools', Service::class);
        yield MenuItem::linkToCrud('Categories', 'fa fa-list', Category::class);
        yield MenuItem::linkToCrud('Menu', 'fa fa-utensils', Menu::class);
        // yield MenuItem::linkToCrud('Utilisateur', 'fa fa-user', User::class);
    }

    private function getNewContactsCount(): int
    {
        return $this->entityManager->getRepository(Contact::class)
            ->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.isRead = false') // Assurez-vous que le champ `isRead` existe dans votre entité `Contact`
            ->getQuery()
            ->getSingleScalarResult();
    }

    private function getNewBookingsCount(): int
    {
    return $this->entityManager->getRepository(Booking::class)
        ->createQueryBuilder('b')
        ->select('COUNT(b.id)')
        ->where('b.isVerified IS NULL') // Compte les réservations non vérifiées
        ->getQuery()
        ->getSingleScalarResult();
    }
}
