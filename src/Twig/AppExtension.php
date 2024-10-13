<?php


namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use App\Service\BookingService;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    private $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function getGlobals(): array
    {
        return [
            'bookingStatus' => $this->bookingService->getBookingStatus(),
        ];
    }
}
