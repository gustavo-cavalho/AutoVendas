<?php

namespace App\Interfaces;

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
     * @param array|null $roles an array of roles if need
     *
     * @return object an entity that DTO represents
     */
    public function ToEntity(?array $roles = null): object;

    /**
     * Validade all fields in the DTO.
     *
     * @throws ValidationException if validation fails
     */
    public function validate(): void;
}
