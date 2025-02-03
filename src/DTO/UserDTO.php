<?php

namespace App\DTO;

use App\Entity\User;
use App\Interfaces\Auth\UserDTOInterface;
use App\Validator as Ensure;
use Symfony\Component\Validator\Constraints as Assert;

class UserDTO extends AbstractDTO implements UserDTOInterface
{
    public const TO_LOGIN = 'login';
    public const TO_REGISTER = 'registration';

    /**
     * @Assert\Email(groups={"registration", "login"})
     */
    private string $email;

    /**
     * @Ensure\StrongPassword(groups={"registration", "login"})
     */
    private string $password;

    /**
     * @Assert\Length(max=255)
     *
     * @Ensure\ValidName(groups={"registration"})
     */
    private ?string $name;

    public function __construct($email, $password, $name = null)
    {
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
    }

    /**
     * Convert the DTO to an entity.
     *
     * @example - ['roles' => ['ROLE_USER', 'ROLE_ADMIN']]
     *
     * @see App\Interfaces\DTOInterface
     */
    public function toEntity(): User
    {
        $user = new User();

        return $user
            ->setEmail($this->email)
            ->setPassword($this->password)
            ->setName($this->name);
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
