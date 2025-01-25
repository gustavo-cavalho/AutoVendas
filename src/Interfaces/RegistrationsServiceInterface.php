<?php

namespace App\Interfaces;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Exceptions\IdentityAlreadyExistsException;

interface RegistrationsServiceInterface
{
    /**
     * Register a new user and hashes the password.
     *
     * @param UserDTO $userDTO
     * @return User
     * @throws IdentityAlreadyExistsException
     */
  public function register(UserDTO $userDTO): User;
}