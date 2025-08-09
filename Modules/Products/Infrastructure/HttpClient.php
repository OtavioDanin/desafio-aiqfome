<?php

declare(strict_types=1);

namespace Modules\Products\Infrastructure;

use Hyperf\Guzzle\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use Modules\Products\Application\HttpClientInterface;

class HttpClient implements HttpClientInterface
{
    public function __construct(protected ClientFactory $clientFactory) {}

    public function get(string $url, array $options = []): array
    {
        return $this->request('GET', $url, $options);
    }

    public function post(string $url, array $data = [], array $options = []): ?array
    {
        $options['json'] = $data;
        return $this->request('POST', $url, $options);
    }

    public function put(string $url, array $data = [], array $options = []): ?array
    {
        $options['json'] = $data;
        return $this->request('PUT', $url, $options);
    }

    public function delete(string $url, array $options = []): ?array
    {
        return $this->request('DELETE', $url, $options);
    }

    private function request(string $method, string $url, array $options = []): ?array
    {
        try {
            $client = $this->clientFactory->create(['timeout' => 3.0]);
            $response = $client->request($method, $url, $options);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException) {
            return null;
        }
    }
}
