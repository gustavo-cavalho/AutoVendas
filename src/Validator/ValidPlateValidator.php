<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidPlateValidator extends ConstraintValidator
{
    //  Example: ABC-123
    public const OLD_PLATE_PATTERN = '/^[A-Z]{3}-\d{4}$/';
    //  Example: ABC1D23
    public const MERCOSUL_PLATE_PATTERN = '/^[A-Z]{3}\d[A-Z]\d{2}$/';

    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\ValidPlate $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        $isValidPlate = preg_match(self::OLD_PLATE_PATTERN, $value)
            || preg_match(self::MERCOSUL_PLATE_PATTERN, $value);

        if ($isValidPlate) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
