<?php

namespace App\Service\Api;

use App\Interfaces\ApiServiceInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class ApiClientService implements ApiServiceInterface
{
    private HttpClientInterface $httpClient;
    private string $baseUrl;

    public function __construct(HttpClientInterface $httpClient, string $baseUrl)
    {
        $this->httpClient = $httpClient;
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    /**
     * Validates the provided value (abstract method, to be implemented by subclasses).
     *
     * @param mixed $value the value to be validated
     */
    abstract public function validate($value);

    /**
     * Makes an HTTP request and returns the response as an array.
     *
     * @param string $method   the HTTP method (GET, POST, PUT, DELETE)
     * @param string $endpoint the API endpoint
     * @param array  $options  The options for the request (e.g., query parameters, JSON data).
     *
     * @return array the response data as an array
     *
     * @throws \Exception if the request fails
     */
    public function request(string $method, string $endpoint, array $options = []): array
    {
        try {
            $response = $this->httpClient->request($method, "{$this->baseUrl}/$endpoint", $options);

            return $response->toArray();
        } catch (\Exception $e) {
            throw new \RuntimeException('An unexpected error occurred while processing the request. Error: '.$e->getMessage());
        }
    }

    /**
     * Sends a GET request to the given endpoint.
     *
     * @param string $endpoint    the API endpoint
     * @param array  $queryParams the query parameters to include in the request
     *
     * @return array the response data as an array
     */
    public function get(string $endpoint, array $queryParams = []): array
    {
        return $this->request('GET', $endpoint, ['query' => $queryParams]);
    }

    /**
     * Sends a POST request to the given endpoint with the provided data.
     *
     * @param string $endpoint the API endpoint
     * @param array  $data     the data to send in the request body
     *
     * @return array the response data as an array
     */
    public function post(string $endpoint, array $data = []): array
    {
        return $this->request('POST', $endpoint, ['json' => $data]);
    }

    /**
     * Sends a PUT request to the given endpoint with the provided data.
     *
     * @param string $endpoint the API endpoint
     * @param array  $data     the data to send in the request body
     *
     * @return array the response data as an array
     */
    public function put(string $endpoint, array $data = []): array
    {
        return $this->request('PUT', $endpoint, ['json' => $data]);
    }

    /**
     * Sends a DELETE request to the given endpoint.
     *
     * @param string $endpoint the API endpoint
     *
     * @return array the response data as an array
     */
    public function delete(string $endpoint): array
    {
        return $this->request('DELETE', $endpoint);
    }
}
