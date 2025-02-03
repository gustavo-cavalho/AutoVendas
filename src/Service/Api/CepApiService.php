<?php

namespace App\Service\Api;

use App\DTO\AddressDTO;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CepApiService extends ApiClientService
{
    private const MIN_SIMILARITY = 85;

    public function __construct(HttpClientInterface $httpClient)
    {
        parent::__construct($httpClient, 'https://opencep.com/v1');
    }

    /**
     * Validates the AddressDTO by comparing its values with the response from the API.
     *
     * @param AddressDTO $value the AddressDTO object that will be validated
     *
     * @throws ValidatorException if any address component (street, neighborhood, city, state)
     *                            has a similarity below the threshold
     */
    public function validate($value): void
    {
        /**
         * @var AddressDTO $value
         */
        $response = $this->get("/{$value->getCep()}");

        $this->validateAddressComponent('Street', $value->getStreet(), $response['logradouro']);
        $this->validateAddressComponent('Neighborhood', $value->getNeighborhood(), $response['bairro']);
        $this->validateAddressComponent('City', $value->getCity(), $response['localidade']);
        $this->validateAddressComponent('State', $value->getState(), $response['uf']);
    }

    /**
     * Validates a specific address component by comparing it to the API response.
     *
     * @param string $componentName The name of the address component being validated (e.g., 'Street', 'Neighborhood').
     * @param string $expectedValue the expected value from the DTO
     * @param string $actualValue   the actual value from the API response
     *
     * @throws ValidatorException if the similarity between the expected and actual values is too low
     */
    private function validateAddressComponent(string $componentName, string $expectedValue, string $actualValue): void
    {
        $similarity = $this->calculateSimilarity($expectedValue, $actualValue);

        if ($similarity <= self::MIN_SIMILARITY) {
            throw new ValidatorException("{$componentName} is too different from expected.");
        }
    }

    /**
     * Calculates the similarity percentage between two strings.
     *
     * This method uses the PHP `similar_text` function to compare two strings in a case-insensitive
     * manner and returns the percentage of similarity between them.
     *
     * @param string $string1 the first string to compare
     * @param string $string2 the second string to compare
     *
     * @return float Returns a similarity percentage (0.0 to 100.0) between the two strings.
     */
    private function calculateSimilarity(string $string1, string $string2): float
    {
        similar_text(strtolower($string1), strtolower($string2), $percent);

        return $percent;
    }
}
