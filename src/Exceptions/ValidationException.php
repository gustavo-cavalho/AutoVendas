<?php

namespace App\Exceptions;

use App\Interfaces\MultipleErrosInterface;

/**
 * Exception to be thrown when a validation fails.
 */
class ValidationException extends \Exception implements MultipleErrosInterface
{
    /**
     * A var that contains the errors from the validation.
     */
    private $errors;

    public function __construct(?string $message = null, $errors = null)
    {
        parent::__construct($message, 400);
        $this->errors = $errors;
    }

    /**
     * Method to get multiple errors from a exception.
     *
     * @return array an array of errors
     *
     * @see App\Interfaces\MultipleErrosInterface
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
