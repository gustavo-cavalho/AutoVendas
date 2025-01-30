<?php

namespace App\Interfaces\Auth;

use App\DTO\UserDTO;

/**
 * Interface to define a Login service.
 */
interface LoginServiceInterface
{
    /**
     * Does the authentication.
     *
     * @param UserDTO $payload the nescessary data to authenticate
     *
     * @return string the token
     *
     * @throws InvalidCredentialsException if password or email is invalid
     */
    public function autenticate(UserDTOInterface $payload): string;
}
