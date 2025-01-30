<?php

namespace App\DTO;

use App\Entity\User;
use App\Exceptions\ValidationException;
use App\Interfaces\Auth\UserDTOInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserDTO implements UserDTOInterface
{
    public const IS_VALID_TO_LOGIN = ['login'];
    public const IS_VALID_TO_REGISTER = ['registration'];

    /**
     * @Assert\Email(groups={"registration", "login"})
     */
    private string $email;

    /**
     * @Assert\StrongPassword(groups={"registration", "login})
     */
    private string $password;

    /**
     * @Assert\Length(max=255)
     *
     * @Assert\ValidName(groups={"registration"})
     */
    private string $name;

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
            ->setEmail($this->email)
            ->setPassword($this->password)
            ->setName($this->name);

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
    public function validate(ValidatorInterface $validator, array $groups): void
    {
        $erros = $validator->validate($this, null, $groups);

        if ($erros->count() > 0) {
            throw new ValidationException('Invalid data', (array) $erros);
        }
    }

    public function getIdentifier(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
