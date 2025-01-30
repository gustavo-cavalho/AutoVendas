<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class StrongPasswordValidator extends ConstraintValidator
{
    public const DEV_PASSWORD_PATTERN = '/^.{6,}$/';
    public const PROD_PASSWORD_PATTERN = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,255}$/';

    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\StrongPassword $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if (preg_match(self::DEV_PASSWORD_PATTERN, $value)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
