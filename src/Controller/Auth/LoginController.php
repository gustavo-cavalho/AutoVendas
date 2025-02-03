<?php

namespace App\Controller\Auth;

use App\DTO\UserDTO;
use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\ValidationException;
use App\Interfaces\Auth\LoginServiceInterface;
use App\Repository\UserRepository;
use App\Service\Auth\JWTService;
use App\Traits\Util\JsonRequestUtil;
use App\Traits\Util\JsonResponseUtil;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LoginController extends AbstractController
{
    use JsonResponseUtil;
    use JsonRequestUtil;

    private LoginServiceInterface $loginService;
    private ValidatorInterface $validator;

    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        ValidatorInterface $validator
    ) {
        $this->loginService = new JWTService($jwtManager, $userRepository, $passwordHasher);
        $this->validator = $validator;
    }

    /**
     * @Route("/login", name="login_check", methods={"POST"})
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $data = $this->getJsonBodyFields($request, ['email', 'password']);

            $userDTO = new UserDTO(
                $data['email'],
                $data['password']
            );

            $userDTO->validate($this->validator, [UserDTO::TO_LOGIN]);

            $token = $this->loginService->autenticate($userDTO);

            $json = [
                'user' => $userDTO->getIdentifier(),
                'token' => $token,
            ];

            return $this->statusOk('You are logged in', $json);
        } catch (BadRequestHttpException $e) {
            return $this->errBadRequest($e->getMessage());
        } catch (ValidationException $e) {
            return $this->errBadRequest($e->getMessage());
        } catch (InvalidCredentialsException $e) {
            return $this->errBadRequest($e->getMessage());
        } catch (\Exception $e) {
            return $this->errInteralServer('Sorry, but some error just ocurred. :(');
        }
    }
}
