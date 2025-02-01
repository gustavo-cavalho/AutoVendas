<?php

namespace App\Validator;

use App\DTO\AddressDTO;
use App\Exceptions\ValidationException;
use App\Service\Api\ApiClientService;
use App\Service\Api\CepApiService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AddressMatchesCepValidator extends ConstraintValidator
{
    private const MIN_SIMILARITY_REQ = 85;

    private ApiClientService $apiClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->apiClient = new CepApiService($httpClient);
    }

    public function validate($value, Constraint $constraint)
    {
        /*
         * @var AddressDTO $value
         */

        if (!$constraint instanceof AddressMatchesCep) {
            throw new UnexpectedTypeException($constraint, AddressMatchesCep::class);
        }

        if (!$value instanceof AddressDTO) {
            throw new UnexpectedValueException($value, AddressDTO::class);
        }

        $endpoint = "{$value->getCep()}";
        $response = $this->apiClient->get($endpoint);

        if (isset($response['error'])) {
            $this->context->buildViolation('Error to consult the CEP')
                ->atPath('cep')
                ->addViolation();

            return;
        }

        try {
            $this->apiClient->validate($value);
        } catch (ValidationException $e) {
            $this->context->buildViolation($e->getMessage())
                ->atPath('address')
                ->addViolation();
        }
    }
}
