<?php

namespace App\Exceptions;

use App\Interfaces\MultipleErrosInterface;

class ValidationException extends \Exception implements MultipleErrosInterface
{
  private array $errors;

  public function __construct(string $message = NULL, array $errors = NULL)
  {
      parent::__construct($message, 400);
      $this->errors = $errors;
  }

  public function getErrors(): array
  {
     return $this->errors;
  }
}