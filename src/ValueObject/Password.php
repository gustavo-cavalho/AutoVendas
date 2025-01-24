<?php

namespace App\ValueObject;

use App\Interfaces\ValueObjectInterface;

class Password implements ValueObjectInterface
{
  private string $password;

  function __construct(string $password)
  {
    $this->password = $password;
  }

  /**
   * @see ValueObjectInterface
   */
  function processToEntity(): string
  {
    return $this->password;
  }
  
  /**
   * @see ValueObjectInterface
   */
  function validate(): bool
  { 
    /**
     * min size: 4, max size: 16
     * must contains: 1 up case letter, 1 down case letter
     * 1 number and 1 especial char 
     */
    $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{4,16}$/';

    return preg_match($regex, $this->password);
  }
}