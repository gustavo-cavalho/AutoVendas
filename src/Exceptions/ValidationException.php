<?php

namespace App\Exceptions;

use App\Interfaces\MultipleErrosInterface;

/**
 * Exception to be thrown when a validation fails.
 */
class ValidationException extends \Exception implements MultipleErrosInterface
{
    /**
     * An array that contains the errors from the validation.
     * The key is the field name and the value is the error message.
     */
    private array $errors;

    public function __construct(?string $message = null, ?array $errors = null)
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
    public function getErrors(): array
    {
        return $this->errors;
    }
}
