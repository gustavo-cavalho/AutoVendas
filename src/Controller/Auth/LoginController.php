<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Exceptions\InvalidCredentialsException;
use App\Interfaces\Auth\LoginServiceInterface;
use App\Repository\UserRepository;
use App\Service\Auth\JWTService;
use App\Traits\Util\JsonRequestUtil;
use App\Traits\Util\JsonResponseUtil;
use App\ValueObject\User\Email;
use App\ValueObject\User\Name;
use App\ValueObject\User\Password;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    use JsonResponseUtil;
    use JsonRequestUtil;

    private LoginServiceInterface $loginService;

    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->loginService = new JWTService($jwtManager, $userRepository, $passwordHasher);
    }

    /**
     * @Route("/login", name="login_check", methods={"POST"})
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $data = $this->getJsonBodyFields($request, ['email', 'password']);

            $userDTO = new UserDTO(
                new Password($data['password']),
                new Email($data['email']),
                new Name($data['name'])
            );

            $token = $this->loginService->autenticate($userDTO);
        } catch (BadRequestHttpException $e) {
            return $this->errBadRequest($e->getMessage());
        } catch (InvalidCredentialsException $e) {
            return $this->errBadRequest($e->getMessage());
        }

        $json = [
            'user' => $userDTO->getIdentifier(),
            'token' => $token,
        ];

        return $this->statusOk('You are logged in', $json);
    }
}
