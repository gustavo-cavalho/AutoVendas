<?php

namespace App\Controller\Auth;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Exceptions\IdentityAlreadyExistsException;
use App\Exceptions\ValidationException;
use App\Interfaces\Auth\RegistrationsServiceInterface;
use App\Repository\UserRepository;
use App\Service\Auth\UserRegistrationService;
use App\Traits\Util\JsonRequestUtil;
use App\Traits\Util\JsonResponseUtil;
use App\Traits\Util\SerializerUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    use JsonResponseUtil;
    use JsonRequestUtil;
    use SerializerUtil;

    private RegistrationsServiceInterface $registrationsService;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->registrationsService = new UserRegistrationService($userRepository, $passwordHasher);
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @Route("/register", name="user_registration", methods={"POST"})
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $data = $this->getJsonBodyFields($request, ['email', 'password', 'name']);

            $userDTO = new UserDTO(
                $data['email'],
                $data['password'],
                $data['name']
            );

            $userDTO->validate($this->validator, [UserDTO::TO_REGISTER]);

            $user = $this->registrationsService->register($userDTO);

            $user = $this->serialize($user, [User::SERIALIZE_SHOW]);

            return $this->statusCreated('User created', $user);
        } catch (BadRequestHttpException $e) {
            return $this->errBadRequest($e->getMessage());
        } catch (ValidationException $e) {
            return $this->errBadRequest($e->getMessage(), $e->getErrors());
        } catch (IdentityAlreadyExistsException $e) {
            return $this->errConflict($e->getMessage());
        } catch (\Exception $e) {
            return $this->errInteralServer('Sorry, but some error just ocurred. :(');
        }
    }
}
