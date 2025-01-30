<?php

namespace App\Interfaces\Auth;

use App\Interfaces\DTOInterface;

interface UserDTOInterface extends DTOInterface
{
    public function getPassword(): string;
}
