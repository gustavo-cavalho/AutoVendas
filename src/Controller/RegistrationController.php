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
use App\Traits\JsonRequestUtil;
use App\Traits\JsonResponseUtil;
use App\ValueObject\Email;
use App\ValueObject\Name;
use App\ValueObject\Password;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface as SymfonySerializerInterface;

class RegistrationController extends AbstractController
{
    use JsonResponseUtil;
    use JsonRequestUtil;

    private RegistrationsServiceInterface $registrationsService;
    private SerializerInterface $serializer;

    public function __construct(
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
        try {
            $data = $this->getJsonBodyFields($request, ['email', 'password', 'name']);
        } catch (BadRequestHttpException $e) {
            return $this->errBadRequest($e->getMessage());
        }

        $userDTO = new UserDTO(
            new Email($data['email']),
            new Password($data['password']),
            new Name($data['name'])
        );

        try {
            $userDTO->validate();
            $user = $this->registrationsService->register($userDTO);
        } catch (ValidationException $e) {
            return $this->errBadRequest($e->getMessage(), $e->getErrors());
        } catch (IdentityAlreadyExistsException $e) {
            return $this->errBadRequest($e->getMessage());
        }

        $user = $this->serializer->serialize($user, ['show_user']);

        return $this->statusCreated('User created', $user);
    }
}
