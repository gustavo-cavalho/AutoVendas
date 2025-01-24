<?php

namespace App\ValueObject;

use App\Interfaces\ValueObjectInterface;

class Email implements ValueObjectInterface
{
  private string $email;

  function __construct(string $email)
  {
    $this->email = $email;
  }

  /**
   * @see ValueObjectInterface
   */
  function processToEntity(): string
  {
      return $this->email;
  }
  
  /**
   * @see ValueObjectInterface
   */
  public function validate(): bool
  {
    return filter_var($this->email, FILTER_VALIDATE_EMAIL);
  }
}