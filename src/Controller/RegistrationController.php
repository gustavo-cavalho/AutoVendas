<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Repository\UserRepository;
use App\ValueObject\Email;
use App\ValueObject\Password;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    private UserRepository $userRepository;

    function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/register", name="user_registration", methods={"POST"})
     */
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(
                [
                    'message' => 'No data'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $userDTO = new UserDTO(
            new Email($data['email']),
            new Password($data['password']),
        );

        $err = $userDTO->validate();
        if ($err) {
            return $this->json(
                [
                    'message' => 'Invalid data',
                    'errors' => $err
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $user = $this->userRepository->findByEmail($userDTO->getEmail());
        if ($user) {
            return $this->json(
                [
                    'message' => 'User already exists'
                ],
                Response::HTTP_CONFLICT
            );
        }

        $user = $userDTO->ToEntity();

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $user->getPassword()
        );
        $user->setPassword($hashedPassword);

        $this->userRepository->add($user, true);
        $user = null;

        return $this->json(
            [
                'message' => 'User created'
            ],
            Response::HTTP_CREATED
        );
    }
}
