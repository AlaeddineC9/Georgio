<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class BookingDate extends Constraint
{
    public $message = 'La date de réservation doit être dans le futur et pendant les heures d\'ouverture.';
}