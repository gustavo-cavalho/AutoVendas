<?php

namespace App\Service\Auth;

use App\Exceptions\InvalidCredentialsException;
use App\Interfaces\Auth\LoginServiceInterface;
use App\Interfaces\Auth\UserDTOInterface;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class JWTService implements LoginServiceInterface
{
    private JWTTokenManagerInterface $jwtManager;
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->jwtManager = $jwtManager;
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function autenticate(UserDTOInterface $payload): string
    {
        $user = $this->userRepository->findByEmail($payload->getIdentifier());

        if (!$user) {
            throw new InvalidCredentialsException('User not found with email: '.$payload->getIdentifier());
        }

        if (!$this->passwordHasher->isPasswordValid($user, $payload->getPassword())) {
            throw new InvalidCredentialsException('Invalid password');
        }

        return $this->jwtManager->create($user);
    }
}
