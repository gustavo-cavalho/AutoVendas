<?php

namespace App\ValueObject\User;

use App\Interfaces\ValueObjectInterface;

/**
 * Represents a password.
 *
 * @author Gustavo Carvalho
 *
 * @version 1.0
 */
class Password implements ValueObjectInterface
{
    private string $password;

    public function __construct(string $password)
    {
        $this->password = $password;
    }

    /**
     * @see ValueObjectInterface
     */
    public function getValue(): string
    {
        return $this->password;
    }

    /**
     * @see ValueObjectInterface
     */
    public function validate(): bool
    {
        /**
         * min size: 4, max size: 16
         * must contains: 1 up case letter, 1 down case letter
         * 1 number and 1 especial char.
         */
        $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{4,16}$/';

        return preg_match($regex, $this->password);
    }
}
