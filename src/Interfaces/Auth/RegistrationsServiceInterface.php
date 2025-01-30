<?php

namespace App\Interfaces\Auth;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Exceptions\IdentityAlreadyExistsException;

/**
 * Contains methods to register a new user.
 */
interface RegistrationsServiceInterface
{
    /**
     * Register a new user and hashes the password.
     *
     * @param UserDTO $userDTO the user to be registered
     *
     * @return User the registered user Entity
     *
     * @throws IdentityAlreadyExistsException if the user already exists
     */
    public function register(UserDTOInterface $userDTO): User;
}
