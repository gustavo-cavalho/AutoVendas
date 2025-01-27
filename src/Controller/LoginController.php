<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Exceptions\InvalidCredentialsException;
use App\Interfaces\LoginServiceInterface;
use App\Repository\UserRepository;
use App\Service\JWTService;
use App\Traits\JsonRequestUtil;
use App\Traits\JsonResponseUtil;
use App\ValueObject\Email;
use App\ValueObject\Password;
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
                new Email($data['email']),
                new Password($data['password']),
            );

            $token = $this->loginService->autenticate($userDTO);
        } catch (BadRequestHttpException $e) {
            return $this->errBadRequest($e->getMessage());
        } catch (InvalidCredentialsException $e) {
            return $this->errBadRequest($e->getMessage());
        }

        $json = [
            'user' => $userDTO->getEmail()->getValue(),
            'token' => $token,
        ];

        return $this->statusOk('You are logged in', $json);
    }
}
