<?php


namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use App\Service\BookingService;
use App\Service\ReservationFormService;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    private $bookingService;

    public function __construct(BookingService $bookingService, ReservationFormService $reservationFormService)
    {
        $this->bookingService = $bookingService;
        $this->reservationFormService = $reservationFormService;
    }

    public function getGlobals(): array
    {
        $reservationForm = $this->reservationFormService->createReservationForm()->createView();
        return [
            'bookingStatus' => $this->bookingService->getBookingStatus(),
            'reservationForm' => $reservationForm,
        ];
    }
}
