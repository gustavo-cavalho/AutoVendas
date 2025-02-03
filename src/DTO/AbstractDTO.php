<?php

namespace App\DTO;

use App\Exceptions\ValidationException;
use App\Interfaces\DTOInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractDTO implements DTOInterface
{
    public function validate(ValidatorInterface $validator, array $groups): void
    {
        $errors = $validator->validate($this, null, $groups);

        if ($errors->count() > 0) {
            throw new ValidationException('The data contains invalid fields', (string) $errors);
        }
    }

    abstract public function toEntity();

    abstract public function getIdentifier();

    // abstract public function normalize();
}
