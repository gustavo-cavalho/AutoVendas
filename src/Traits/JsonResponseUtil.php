<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait JsonResponseUtil
{
    private function successResponse(string $message, $data = [], int $status = Response::HTTP_OK): JsonResponse
    {
        $jsonResponse = [
            'message' => $message,
        ];

        if (!empty($data)) {
            $jsonResponse['data'] = $data;
        }

        return $this->json($jsonResponse, $status);
    }

    private function failureResponse(string $message, array $errors = [], int $status = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        $jsonResponse = [
            'message' => $message,
        ];

        if (!empty($errors)) {
            $jsonResponse['errors'] = $errors;
        }

        return $this->json($jsonResponse, $status);
    }

    // /// Success Response /////

    public function statusOk(string $message, $data = []): JsonResponse
    {
        return $this->successResponse($message, $data, Response::HTTP_OK);
    }

    public function statusCreated(string $message, $data = []): JsonResponse
    {
        return $this->successResponse($message, $data, Response::HTTP_CREATED);
    }

    public function statusNoContent(string $message): JsonResponse
    {
        return $this->successResponse($message, [], Response::HTTP_NO_CONTENT);
    }

    // /// Errors Response /////

    public function errBadRequest(string $message, array $errors = []): JsonResponse
    {
        return $this->failureResponse($message, $errors, Response::HTTP_BAD_REQUEST);
    }

    public function errNotFound(string $message): JsonResponse
    {
        return $this->failureResponse($message, [], Response::HTTP_NOT_FOUND);
    }

    public function errUnauthorized(string $message): JsonResponse
    {
        return $this->failureResponse($message, [], Response::HTTP_UNAUTHORIZED);
    }
}
