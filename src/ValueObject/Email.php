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
   * Convert the objects in a format that the entity reconize and if needed
   * do some action.
   * @example -- hashes a password before return it.
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