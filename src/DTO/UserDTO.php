<?php

namespace App\DTO;

use App\Entity\User;
use App\Exceptions\ValidationException;
use App\Interfaces\Auth\UserDTOInterface;
use App\ValueObject\User\Email;
use App\ValueObject\User\Name;
use App\ValueObject\User\Password;

class UserDTO implements UserDTOInterface
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

    public function __construct($email, $password, $name)
    {
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
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

        $user
            ->setEmail($this->email->getValue())
            ->setPassword($this->password->getValue())
            ->setName($this->name->getValue());

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

        if (!$this->email->validate()) {
            $err['email'] = 'Invalid email';
        }

        if (!$this->password->validate()) {
            $err['password'] = 'Invalid password';
        }

        if (!$this->name->validate()) {
            $err['name'] = 'Invalid Name';
        }

        if (!empty($err)) {
            throw new ValidationException('Invalid data', array_filter($err));
        }
    }

    public function getIdentifier(): string
    {
        return $this->email->getValue();
    }

    public function getPassword(): string
    {
        return $this->password->getValue();
    }
}
