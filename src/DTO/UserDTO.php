<?php

namespace App\DTO;

use App\Entity\User;
use App\Exceptions\ValidationException;
use App\Interfaces\DTOInterface;
use App\ValueObject\Email;
use App\ValueObject\Name;
use App\ValueObject\Password;

class UserDTO implements DTOInterface
{
    /**
     * @see App\ValueObject\Email
     */
    private Email $email;

    /**
     * @see App\ValueObject\Password
     */
    private Password $password;

    /**
     * @see App\ValueObject\Name
     */
    private Name $name;

    public function __construct(Email $email, Password $password, Name $name)
    {
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
    }

    /**
     * Convert the data to an entity.
     *
     * @param array|null $roles an array of roles
     *
     * @see App\Interfaces\DTOInterface
     */
    public function ToEntity(?array $roles = null): User
    {
        $user = new User();

        $user->setEmail(
            $this->getEmail()->getValue()
        );
        $user->setPassword(
            $this->getPassword()->getValue()
        );
        $user->setName(
            $this->getName()->getValue()
        );
        
        if ($roles) {
            $user->setRoles($roles);
        }

        return $user;
    }

    /**
     * Check if the data is valid.
     *
     * @see App\Interfaces\DTOInterface
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

        if (!$this->getName()->validate()) {
            $err['name'] = 'Empty Name';
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

    public function getName(): Name
    {
        return $this->name;
    }
}
