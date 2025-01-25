<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Exceptions\IdentityAlreadyExistsException;
use App\Exceptions\ValidationException;
use App\Interfaces\RegistrationsServiceInterface;
use App\Interfaces\SerializerInterface;
use App\Repository\UserRepository;
use App\Service\UserRegistrationService;
use App\Service\UserSerializerService;
use App\Traits\JsonResponseUtil;
use App\ValueObject\Email;
use App\ValueObject\Password;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface as SymfonySerializerInterface;

class RegistrationController extends AbstractController
{
    use JsonResponseUtil;

    private RegistrationsServiceInterface $registrationsService;
    private SerializerInterface $serializer;

    function __construct(
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        SymfonySerializerInterface $serializer
        ) {
        $this->registrationsService = new UserRegistrationService($userRepository, $passwordHasher);
        $this->serializer = new UserSerializerService($serializer);
    }

    /**
     * @Route("/register", name="user_registration", methods={"POST"})
     */
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->errBadRequest('Invalid data');
        }

        $userDTO = new UserDTO(
            new Email($data['email']),
            new Password($data['password']),
        );
        
        try {
            $userDTO->validate();
            $user = $this->registrationsService->register($userDTO);
        } catch (ValidationException $e) {
            return $this->errBadRequest($e->getMessage(), $e->getErrors());
        } catch (IdentityAlreadyExistsException $e) {
            return $this->errBadRequest($e->getMessage());
        }

        // TODO: implement serializer

        return $this->statusCreated('User created', $user);
    }
}
