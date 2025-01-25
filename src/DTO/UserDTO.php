<?php

namespace App\DTO;

use App\Entity\User;
use App\Exceptions\ValidationException;
use App\Interfaces\DTOConverterInterface;
use App\Interfaces\DTOValidatorInterface;
use App\ValueObject\Email;
use App\ValueObject\Password;

class UserDTO implements DTOValidatorInterface, DTOConverterInterface
{
  private Email $email;

  private Password $password;

  function __construct(Email $email, Password $password)
  {
    $this->email = $email;
    $this->password = $password;
  }

  /**
   * @see DTOConverterInterface 
   */
  public function ToEntity(?array $roles = null): User
  {
    $user = new User();

    $user->setEmail(
      $this->getEmail()->processToEntity()
    );
    $user->setPassword(
      $this->getPassword()->processToEntity()
    );
    if ($roles) {
      $user->setRoles($roles);
    }
    
    return $user;
  }

  /**
   * @see DTOValidatorInterface
   */
  public function validate(): void
  {
    // TODO: add constants that represents de error mensage
    
    $err[] = $this->email->validate() ? null : 'Invalid email';
    $err[] = $this->password->validate() ? null : 'Invalid password';

    if (! empty($err)) {
      throw new ValidationException('Invalid data', $err);
    }
  }

  public function getEmail(): Email
  {
    return $this->email;
  }

  public function getPassword(): Password
  {
    return $this->password;
  }
}