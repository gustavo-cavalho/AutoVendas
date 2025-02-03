<?php

namespace App\Interfaces;

/**
 * Contains methods to get multiple errors from a exception.
 *
 * Must be implemented in a class that extends Exception
 */
interface MultipleErrosInterface
{
    /**
     * Method to get multiple errors from a exception.
     *
     * @return mixed the errors
     */
    public function getErrors();
}
