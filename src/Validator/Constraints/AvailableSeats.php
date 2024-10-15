<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class AvailableSeats extends Constraint
{
    public $message = 'Le nombre total de réservations pour ce créneau horaire a atteint sa limite de 60 personnes.';
}