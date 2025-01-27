<?php

namespace App\Interfaces;

/**
 * Interface to define a value object.
 */
interface ValueObjectInterface
{
    /**
     * Return the value of the value object.
     *
     * @example if the value object is an email
     * return string that represents the email
     */
    public function getValue();

    /**
     * Validate the value object with a custom validation
     * based on the business rules.
     */
    public function validate(): bool;
}
