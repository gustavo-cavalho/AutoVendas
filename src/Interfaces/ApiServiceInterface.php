<?php

namespace App\Interfaces;

interface ApiServiceInterface
{
    public function request(string $method, string $endpoint, array $options = []): array;
    public function get(string $endpoint, array $queryParams = []): array;
    public function post(string $endpoint, array $data = []): array;
    public function put(string $endpoint, array $data = []): array;
    public function delete(string $endpoint): array;
}