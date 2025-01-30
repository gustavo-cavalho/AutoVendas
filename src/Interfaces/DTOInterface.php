<?php

namespace App\Interfaces;

use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Interface to define a Data Transfer Object.
 *
 * Must be implemented in a class that is a Data Transfer Object
 */
interface DTOInterface
{
    /**
     * Convert the object to an entity.
     *
     * @param array|null $options an array of keys and values
     *                            that can be used to configure the entity
     *
     * @return object an entity that DTO represents
     */
    public function ToEntity(?array $options = null): object;

    /**
     *  Uses the Symfony validator to validate the groups that're requested.
     *
     * @throws ValidationException if has any error
     */
    public function validate(ValidatorInterface $validator, array $groups): void;

    /**
     * Get identifier of the DTO
     * Can be a email, plate e etc.
     */
    public function getIdentifier();
}
