<?php

namespace App\Interfaces;

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
     * @return mixed whatever the authentication method returns
     *
     * @throws InvalidCredentialsException if password or email is invalid
     */
    public function autenticate(UserDTO $payload): mixed;
}
