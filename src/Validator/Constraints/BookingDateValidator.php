<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BookingDateValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\Constraints\BookingDate */

        if (null === $value) {
            return;
        }

        // Votre logique de validation pour la date
        $currentDate = new \DateTime();
        if ($value <= $currentDate) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
            return;
        }

        // Vérifier les créneaux horaires
        $time = $value->format('H:i');

        $morningStart = '12:00';
        $morningEnd = '15:00';
        $eveningStart = '19:30';
        $eveningEnd = '22:30';

        if (
            ($time < $morningStart || $time > $morningEnd) &&
            ($time < $eveningStart || $time > $eveningEnd)
        ) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}