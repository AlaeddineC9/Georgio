<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Booking;

class BookingService
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getBookingStatus(): ?Booking
    {
        $bookingRepository = $this->doctrine->getRepository(Booking::class);
        $bookingStatus = $bookingRepository->findOneBy([]);

        if (!$bookingStatus) {
            $bookingStatus = new Booking();
            $bookingStatus->setCanBook(true); // Valeur par défaut si aucun enregistrement trouvé
        }

        return $bookingStatus;
    }
}
