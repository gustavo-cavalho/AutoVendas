<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

trait JsonRequestUtil
{
    /**
     * Obtém os campos obrigatórios da requisição JSON.
     *
     * @throws BadRequestHttpException
     */
    protected function getJsonBodyFields(Request $request, array $requiredFields): array
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            throw new BadRequestHttpException('Invalid JSON data.');
        }

        $missingFields = array_diff($requiredFields, array_keys($data));

        if (!empty($missingFields)) {
            throw new BadRequestHttpException('Missing required fields: '.implode(', ', $missingFields));
        }

        return array_intersect_key($data, array_flip($requiredFields));
    }
}
