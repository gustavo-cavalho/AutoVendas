<?php

namespace App\Service\Api;

use App\DTO\VehicleDTO;
use App\Entity\Vehicle;
use App\Exceptions\ValidationException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FipeApiService extends ApiClientService
{
    public function __construct(HttpClientInterface $httpClient)
    {
        parent::__construct($httpClient, 'https://parallelum.com.br/fipe/api/v1');
    }

    /**
     * Validate a DTO and sets the integration data for the vehicle by fetching brand, model, and year data
     * from an external API and updating the VehicleDTO object.
     *
     * This method sequentially calls external APIs to retrieve the available car brands, models,
     * and manufacturing years, then matches the provided values from the DTO using the `lookFor` method.
     * If no match is found, a `ValidationException` is thrown.
     *
     * @param VehicleDTO $value the dto that will be validated
     *
     * @throws ValidationException if any of the car's brand, model, or year are invalid or not found
     */
    public function validate($value)
    {
        /**
         * @var VehicleDTO $value
         */
        $url = "{$value->getType()}/marcas";

        /**
         * The 'nome' in the method lookFor is used to specify the field to search for.
         * Example: The URL returns an array of objects that contain 'codigo' and 'nome'.
         */
        $brands = $this->get($url);
        $result = $this->lookFor($brands, $value->getBrand());
        if (empty($result)) {
            throw new ValidationException('Invalid car brand.');
        }
        $value->setBrandIntegration($result['codigo']);

        /*
         * The 'modelos' after the get($url) is used because the route returns an object with
         * 'anos' and 'modelos', where 'modelos' is an array of objects containing 'codigo' and 'nome'.
         */
        $url .= "/{$value->getBrandIntegration()}/modelos";
        $models = $this->get($url)['modelos'];
        $result = $this->lookFor($models, $value->getModel());
        if (empty($result)) {
            throw new ValidationException('Invalid car model.');
        }
        $value->setModelIntegration($result['codigo']);

        /*
         * This route returns an array of objects containing 'codigo' and 'nome' fields.
         */
        $url .= "/{$value->getModelIntegration()}/anos";
        $years = $this->get($url);
        $result = $this->lookFor($years, $value->getManufacturedYear());
        if (empty($result)) {
            throw new ValidationException('Invalid car year.');
        }
        $value->setYearIntegration($result['codigo']);
    }

    /**
     * Retrieves information from the Fipe API using the provided vehicle data from the VehicleDTO.
     *
     * This method ensures that the vehicle's brand, model, and year integration values are set
     * before making the request. If any of these values are missing, a `ValidationException` is thrown.
     *
     * @return array the data retrieved from the Fipe API
     *
     * @throws \InvalidArgumentException if any of the required integration values are missing
     */
    public function getInfoFromFipe(Vehicle $Vehicle): array
    {
        if (empty($Vehicle->getBrandIntegration())) {
            throw new \InvalidArgumentException('Brand integration is missing.');
        }

        if (empty($Vehicle->getModelIntegration())) {
            throw new \InvalidArgumentException('Model integration is missing.');
        }

        if (empty($Vehicle->getYearIntegration())) {
            throw new \InvalidArgumentException('Year integration is missing.');
        }

        return $this->get(
            "{$Vehicle->getType()}/marcas/{$Vehicle->getBrandIntegration()}/modelos/{$Vehicle->getModelIntegration()}/anos/{$Vehicle->getYearIntegration()}"
        );
    }

    /**
     * Searches for a specific value within an array of arrays and returns matching elements.
     *
     * This function filters a multidimensional array, checking if the value of the specified field
     * matches (case-insensitive) the given value.
     *
     * @param array  $data  the array of arrays to be searched
     * @param mixed  $value the value to be compared within the specified field
     * @param string $field the name of the field to be compared (default: 'nome')
     *
     * @return array returns an array containing the elements that match the search criteria
     *
     * ```php
     * $data = [
     *     ['nome' => 'Ferrari', 'codigo' => 30],
     *     ['nome' => 'bmw', 'codigo' => 25],
     *     ['nome' => 'ferrari', 'codigo' => 40]
     * ];
     *
     * $result = lookFor($data, 'ferrari');
     * // Returns:
     * // [
     * //     ['nome' => 'Ferrari', 'codigo' => 30],
     * //     ['nome' => 'ferrari', 'codigo' => 40]
     * // ]
     * ```
     */
    private function lookFor(array $data, $value, string $field = 'nome'): array
    {
        /*
         * The "array_value(...)[0]" is needed cause the function wiithout it returns:
         *   { [34]=> array(2) { ["codigo"]=> string(2) "22" ["nome"]=> string(4) "Ford" } }
         * But, with the "array_value(...)[0]" it retuns:
         *   array(2) { ["codigo"]=> string(2) "22" ["nome"]=> string(4) "Ford" }
         */
        return array_values(
            array_filter($data, function ($item) use ($value, $field) {
                return strtolower($item[$field]) === strtolower($value);
            })
        )[0];
    }
}
