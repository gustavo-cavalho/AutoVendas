<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidNameValidator extends ConstraintValidator
{
    private const ONLY_LETTER_RGX = "/^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/u";

    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\ValidName $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if (preg_match()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
