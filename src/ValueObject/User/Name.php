<?php

namespace App\ValueObject\User;

use App\Interfaces\ValueObjectInterface;

/**
 * Represents a name of a user.
 *
 * @author Gustavo Carvalho
 *
 * @version 1.0
 */
class Name implements ValueObjectInterface
{
    private string $value;

    public function __construct(string $name)
    {
        $this->value = $name;
    }

    /**
     * @see ValueObjectInterface
     */
    public function validate(): bool
    {
        return !empty($this->value)
          && strlen($this->value) >= 3
          && strlen($this->value) <= 255;
    }

    /**
     * @see ValueObjectInterface
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
