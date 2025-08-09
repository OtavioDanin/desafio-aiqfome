<?php

declare(strict_types=1);

namespace Modules\Products\Application;

interface HttpClientInterface
{
    public function get(string $path, array $options = []): array;
    public function post(string $url, array $data = [], array $options = []): ?array;
    public function put(string $url, array $data = [], array $options = []): ?array;
    public function delete(string $url, array $options = []): ?array;
}
