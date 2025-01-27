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

    public function __construct(Email $email, Password $password)
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
        $err = [];
        if (!$this->getEmail()->validate()) {
            $err['email'] = 'Invalid email';
        }

        if (!$this->getPassword()->validate()) {
            $err['password'] = 'Invalid password';
        }

        if (!empty($err)) {
            throw new ValidationException('Invalid data', array_filter($err));
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
