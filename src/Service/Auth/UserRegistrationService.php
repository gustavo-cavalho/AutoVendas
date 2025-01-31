<?php

namespace App\Service\Auth;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Exceptions\IdentityAlreadyExistsException;
use App\Interfaces\Auth\RegistrationsServiceInterface;
use App\Interfaces\Auth\UserDTOInterface;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Service responsible for user registration.
 *
 * @author Gustavo Carvalho
 *
 * @version 1.0
 */
class UserRegistrationService implements RegistrationsServiceInterface
{
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Register a new user and hashes the password.
     *
     * @param UserDTO $userDTO the user to be registered
     *
     * @return User the registered user Entity
     *
     * @throws IdentityAlreadyExistsException if the user already exists
     *
     * @see App\Interfaces\RegistrationsServiceInterface
     */
    public function register(UserDTOInterface $userDTO): User
    {
        $user = $this->userRepository->findByEmail($userDTO->getIdentifier());
        if (!is_null($user)) {
            throw new IdentityAlreadyExistsException('User already exists');
        }

        $user = $userDTO->ToEntity();

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $user->getPassword()
        );
        $user->setPassword($hashedPassword);

        $this->userRepository->add($user, true);

        return $user;
    }
}
