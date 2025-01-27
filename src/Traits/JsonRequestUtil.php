<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Trait that is responsible for handling JSON requests.
 *
 * Provides methods to validate and extract JSON data from a request.
 *
 * @author Gustavo Carvalho
 *
 * @version 1.0
 */
trait JsonRequestUtil
{
    /**
     * Check and extract JSON data from a request.
     *
     * @param Request $request        the request object
     * @param array   $requiredFields an array of required field names
     *
     * @return array an array of extracted JSON data
     *
     * @throws BadRequestHttpException if the JSON data is invalid or missing required fields
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
