<?php

namespace App\Validator;

use App\DTO\AddressDTO;
use App\Service\ApiClientService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AddressMatchesCepValidator extends ConstraintValidator
{
    private const MIN_SIMILARITY_REQ = 85;

    private ApiClientService $apiClient;

    public function __construct(HttpClientInterface $httpClientInterface)
    {
        $this->apiClient = new ApiClientService($httpClientInterface, 'https://opencep.com/v1');
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

        $streetSimilarity = $this->calculateSimilarity($value->getStreet(), $response['logradouro']);
        $neighborhoodSimilarity = $this->calculateSimilarity($value->getNeighborhood(), $response['bairro']);
        $citySimilarity = $this->calculateSimilarity($value->getCity(), $response['localidade']);
        $stateSimilarity = $this->calculateSimilarity($value->getState(), $response['uf']);

        if ($streetSimilarity < self::MIN_SIMILARITY_REQ) {
            $this->context->buildViolation('The name is too diferent from the expected.')
                ->atPath('street')
                ->addViolation();
        }

        if ($neighborhoodSimilarity < self::MIN_SIMILARITY_REQ) {
            $this->context->buildViolation('The name is too diferent from the expected.')
                ->atPath('neighborhood')
                ->addViolation();
        }

        if ($citySimilarity < self::MIN_SIMILARITY_REQ) {
            $this->context->buildViolation('The name is too diferent from the expected.')
                ->atPath('city')
                ->addViolation();
        }

        if ($stateSimilarity < self::MIN_SIMILARITY_REQ) {
            $this->context->buildViolation('The name is too diferent from the expected.')
                ->atPath('state')
                ->addViolation();
        }
    }

    /**
     * Calcula a similaridade entre duas strings.
     */
    private function calculateSimilarity(string $string1, string $string2): float
    {
        similar_text(strtolower($string1), strtolower($string2), $percent);

        return $percent;
    }
}
