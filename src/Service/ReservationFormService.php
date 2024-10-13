<?php

namespace App\Service;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use App\Form\BookingType;
use App\Entity\Booking;

class ReservationFormService
{
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function createReservationForm(): FormInterface
    {
        $bookingEntity = new Booking();
        $form = $this->formFactory->create(BookingType::class, $bookingEntity);

        return $form;
    }
}
