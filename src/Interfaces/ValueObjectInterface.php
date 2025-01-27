<?php

namespace App\Interfaces;

/**
 * Interface to define a value object.
 */
interface ValueObjectInterface
{
    /**
     * Prepare the value object to be converted to an entity
     * in case need do some transformation is possible insert here.
     */
    public function processToEntity();

    /**
     * Validate the value object with a custom validation
     * based on the business rules.
     */
    public function validate(): bool;
}
