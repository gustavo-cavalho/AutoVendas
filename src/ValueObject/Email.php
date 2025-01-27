<?php

namespace App\ValueObject;

use App\Interfaces\ValueObjectInterface;

/**
 * Represents an email.
 *
 * @author Gustavo Carvalho
 *
 * @version 1.0
 */
class Email implements ValueObjectInterface
{
    private string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * @see ValueObjectInterface
     */
    public function getValue(): string
    {
        return $this->email;
    }

    /**
     * @see ValueObjectInterface
     */
    public function validate(): bool
    {
        return filter_var($this->email, FILTER_VALIDATE_EMAIL);
    }
}
