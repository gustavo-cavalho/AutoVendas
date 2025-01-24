<?php

namespace App\ValueObject;

use App\Interfaces\ValueObjectInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Password implements ValueObjectInterface
{
  private string $password;
  private ?UserPasswordHasherInterface $passwordHasher;

  function __construct(string $password, ?UserPasswordHasherInterface $passwordHasher = null)
  {
    $this->password = $password;
    $this->passwordHasher = $passwordHasher;
  }

  /**
   * Convert the objects in a format that the entity reconize and if needed
   * do some action.
   * @example -- hashes a password before return it.
   * @see ValueObjectInterface
   */
  function processToEntity(): string
  {
    $hashedPassword = $this->passwordHasher->hashPassword($this->password);
    $this->password = $hashedPassword;

    return $hashedPassword;
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