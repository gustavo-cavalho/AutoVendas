<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidPhoneValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\ValidPhone $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if ($this->followsPhoneNumberFormat($value)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }

    public function followsPhoneNumberFormat(string $phoneNumber): bool
    {
        // Format: "(00) 91234-5678"
        return (bool) preg_match('/^\(\d{2}\) \d{5}-\d{4}$/', $phoneNumber);
    }
}
