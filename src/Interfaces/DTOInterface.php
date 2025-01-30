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
     * @param array|null $options an array of keys and values
     *                            that can be used to configure the entity
     *
     * @return object an entity that DTO represents
     */
    public function ToEntity(?array $options = null): object;

    /**
     * Validade all fields in the DTO.
     *
     * @throws ValidationException if validation fails
     */
    public function validate(): void;

    /**
     * Get identifier of the DTO
     * Can be a email, plate e etc.
     */
    public function getIdentifier();
}
