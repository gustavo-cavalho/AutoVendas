<?php

namespace App\Traits\Util;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Trait that is responsible for returning JSON responses.
 *
 * This trait is used to provide methods to return a partterned JSON response.
 *
 * @author Gustavo Carvalho
 *
 * @version 1.0
 */
trait JsonResponseUtil
{
    /**
     * Format a success response.
     *
     * @param string       $message the message to be returned
     * @param object|array $data    adcional data to be returned
     * @param int          $status  the HTTP status code to be returned
     *
     * @return JsonResponse Json ready for return
     */
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

    /**
     * Format a failure response.
     *
     * @param string       $message the message to be returned
     * @param object|array $errors  adcional data to be returned
     * @param int          $status  the HTTP status code to be returned
     *
     * @return JsonResponse Json ready for return
     */
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

    /**
     * Response with status 200.
     *
     * @see successResponse
     */
    public function statusOk(string $message, $data = []): JsonResponse
    {
        return $this->successResponse($message, $data, Response::HTTP_OK);
    }

    /**
     * Response with status 201.
     *
     * @see successResponse
     */
    public function statusCreated(string $message, $data = []): JsonResponse
    {
        return $this->successResponse($message, $data, Response::HTTP_CREATED);
    }

    /**
     * Response with status 204.
     *
     * @see successResponse
     */
    public function statusNoContent(string $message): JsonResponse
    {
        return $this->successResponse($message, [], Response::HTTP_NO_CONTENT);
    }

    // /// Errors Response /////

    public function errInteralServer(string $message): JsonResponse
    {
        return $this->failureResponse($message, [], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Response with status 400.
     *
     * @see failureResponse
     */
    public function errBadRequest(string $message, array $errors = []): JsonResponse
    {
        return $this->failureResponse($message, $errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Response with status 404.
     *
     * @see failureResponse
     */
    public function errNotFound(string $message): JsonResponse
    {
        return $this->failureResponse($message, [], Response::HTTP_NOT_FOUND);
    }

    /**
     * Response with status 401.
     *
     * @see failureResponse
     */
    public function errUnauthorized(string $message): JsonResponse
    {
        return $this->failureResponse($message, [], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Response with status 409.
     *
     * @see failureResponse
     */
    public function errConflict(string $message): JsonResponse
    {
        return $this->failureResponse($message, [], Response::HTTP_CONFLICT);
    }
}
