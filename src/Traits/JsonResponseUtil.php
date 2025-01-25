<?php

namespace App\Traits;

use App\Interfaces\MultipleErrosInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

trait JsonResponseUtil
{
    private SerializerInterface $serializer;

    private function successResponse(string $message, ?object $object = null, int $status = Response::HTTP_OK)
    {
        $json = [
            'message' => $message,
        ];
        $object ? $json['data'] = $object : null;

        return $this->json($json, $status);
    }

    private function failureResponse(string $message, ?array $err = null, int $status = Response::HTTP_BAD_REQUEST)
    {
        $json = ['message' => $message];

        if ($err) {
            $json['errors'] = $err;
        }

        return $this->json($json, $status);
    }

    ///// Success returns /////
    public function statusOk(string $message, ?object $object = null)
    {
        return $this->successResponse($message, $object);
    }

    public function statusCreated(string $message, ?object $object = null)
    {
        return $this->successResponse($message, $object, Response::HTTP_CREATED);
    }

    public function statusNoContent(string $message)
    {
        return $this->successResponse($message, null, Response::HTTP_NO_CONTENT);
    }

    ///// Errors returns /////
    public function errBadRequest(string $message, ?array $err = null)
    {
        return $this->failureResponse($message, $err, Response::HTTP_BAD_REQUEST);
    }

    public function errNotFound(string $message)
    {
        return $this->failureResponse($message, null, Response::HTTP_NOT_FOUND);
    }

    public function errUnauthorized(string $message)
    {
        return $this->failureResponse($message, null, Response::HTTP_UNAUTHORIZED);
    }
}