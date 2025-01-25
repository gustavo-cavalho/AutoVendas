<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="user_login", methods={"POST"})
     */
    public function login(/*@[CurrentUser*/ ?User $user): JsonResponse
    {
        if (!$user) {
            return $this->json(
                [
                    'message' => 'Unauthorized',
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $token = 'todo';

        return $this->json(
            [
                'message' => 'Authorized',
                'user' => $user->getUserIdentifier(),
                'token' => $token,
            ],
            Response::HTTP_OK
        );
    }
}
