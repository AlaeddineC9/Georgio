<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Booking;
use App\Entity\Galerie;
use App\Entity\Contact;
use App\Entity\Service;
use App\Entity\Category;
use App\Entity\Menu;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends AbstractDashboardController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        
        $bookings = $this->getPendingBookings($this->entityManager);
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
            ->orderBy('b.date', 'DESC') // Trier par date en ordre décroissant
            ->getQuery()
            ->getResult();
    }

    private function getBookingStatus(EntityManagerInterface $entityManager): ?Booking
{
    // Récupérer l'enregistrement spécifique pour l'activation
    return $entityManager->getRepository(Booking::class)->findOneBy([]);
}
    #[Route('/admin/confirm-booking/{id}', name: 'admin_confirm_booking')]
    public function confirmBooking(Request $request, Booking $booking): Response
    {
        $booking->setIsVerified(true);
        $this->entityManager->flush();

        $this->addFlash('success', 'La réservation a été confirmée avec succès.');

        return $this->redirectToRoute('admin');
    }

    #[Route('/admin/deny-booking/{id}', name: 'admin_deny_booking')]
    public function denyBooking(Request $request, Booking $booking): Response
    {
        $booking->setIsVerified(false);
        $this->entityManager->flush();

        $this->addFlash('success', 'La réservation a été refusée avec succès.');

        return $this->redirectToRoute('admin');
    }

    #[Route('/admin/toggle-booking-status', name: 'toggle_booking_status')]
    public function toggleBookingStatus(EntityManagerInterface $entityManager): Response
    {
        $bookingRepository = $entityManager->getRepository(Booking::class);

        // Vous pouvez ajuster la logique pour cibler un enregistrement spécifique si nécessaire
        $booking = $bookingRepository->findOneBy([]);

        if ($booking) {
            $booking->setCanBook(!$booking->canBook());
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin'); // Redirigez vers votre tableau de bord ou une autre page
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Georgio');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('client', 'fa fa-user', Client::class);
        yield MenuItem::linkToCrud('galerie', 'fa fa-image', Galerie::class);
        yield MenuItem::linkToCrud('contact', 'fa fa-bed', Contact::class);
        yield MenuItem::linkToCrud('booking', 'fa fa-bed', Booking::class);
        yield MenuItem::linkToCrud('service', 'fa fa-bed', Service::class);
        yield MenuItem::linkToCrud('Categories', 'fa fa-list', Category::class);
        yield MenuItem::linkToCrud('Menu', 'fa fa-utensils', Menu::class);
    }
}
