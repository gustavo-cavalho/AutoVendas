<?php

namespace App\DTO;

use App\Entity\User;
use App\Exceptions\ValidationException;
use App\Interfaces\DTOInterface;
use App\ValueObject\User\Email;
use App\ValueObject\User\Name;
use App\ValueObject\User\Password;

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

    public function __construct(array $validatedData)
    {
        $this->email = new Email($validatedData['email']);
        $this->password = new Password($validatedData['password']);
        $this->name = new Name($validatedData['name']);
    }

    /**
     * Convert the DTO to an entity.
     *
     * @param array|null $options is possible to pass extra info if needed
     *
     * @example - ['roles' => ['ROLE_USER', 'ROLE_ADMIN']]
     *
     * @see App\Interfaces\DTOInterface
     */
    public function ToEntity(?array $options = null): User
    {
        $user = new User();

        $user->setEmail(
            $this->getEmail()->getValue()
        )->setPassword(
            $this->getPassword()->getValue()
        )->setName(
            $this->getName()->getValue()
        );

        if (isset($options['roles'])) {
            $roles = $options['roles'];

            if (!is_array($roles)) {
                throw new \InvalidArgumentException('Roles must be an array');
            }

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
            $err['name'] = 'Invalid Name';
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
