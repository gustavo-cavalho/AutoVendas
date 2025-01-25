<?php

namespace App\Service;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Exceptions\IdentityAlreadyExistsException;
use App\Interfaces\RegistrationsServiceInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegistrationService implements RegistrationsServiceInterface
{
  private UserRepository $userRepository;
  private UserPasswordHasherInterface $passwordHasher;

  function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
  {
    $this->userRepository = $userRepository;
    $this->passwordHasher = $passwordHasher;  
  }

  public function register(UserDTO $userDTO): User
  {
    $user = $this->userRepository->findByEmail($userDTO->getEmail());
    if ($user) {
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