<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\BookingRepository;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use App\Entity\Booking;


class AvailableSeatsValidator extends ConstraintValidator
{
    private $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof AvailableSeats) {
            throw new UnexpectedTypeException($constraint, AvailableSeats::class);
        }

        if (null === $value) {
            return;
        }

        // Récupérer l'objet Booking à partir de la racine du formulaire
        $booking = $this->context->getRoot()->getData();

        // Vérifier que $booking est bien une instance de Booking
        if (!$booking instanceof Booking) {
            return;
        }

        // Votre logique pour vérifier la disponibilité
        $date = $booking->getDate();
        $nbGuest = $booking->getNbGuest();

        // Comparer les dates pour la même journée
        $startOfDay = (clone $date)->setTime(0, 0);
        $endOfDay = (clone $date)->setTime(23, 59, 59);

        // Trouver les réservations existantes pour la même journée
        $existingBookings = $this->bookingRepository->createQueryBuilder('b')
            ->where('b.date >= :startOfDay')
            ->andWhere('b.date <= :endOfDay')
            ->setParameter('startOfDay', $startOfDay)
            ->setParameter('endOfDay', $endOfDay)
            ->getQuery()
            ->getResult();

        // Calculer le nombre total d'invités pour chaque créneau spécifique
        $time = $date->format('H:i');
        $morningStart = '12:00';
        $morningEnd = '15:00';
        $eveningStart = '19:30';
        $eveningEnd = '22:30';

        $totalGuests = $nbGuest;
        foreach ($existingBookings as $existingBooking) {
            $bookingTime = $existingBooking->getDate()->format('H:i');
            if (
                ($time >= $morningStart && $time <= $morningEnd && $bookingTime >= $morningStart && $bookingTime <= $morningEnd) ||
                ($time >= $eveningStart && $time <= $eveningEnd && $bookingTime >= $eveningStart && $bookingTime <= $eveningEnd)
            ) {
                $totalGuests += $existingBooking->getNbGuest();
            }
        }

        if ($totalGuests > 60) {
            $this->context->buildViolation($constraint->message)
                ->atPath('nb_guest') // Associer l'erreur au champ nb_guest
                ->addViolation();
        }
    }
}