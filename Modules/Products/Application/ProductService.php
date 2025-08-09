<?php

declare(strict_types=1);

namespace Modules\Products\Application;

use Modules\Products\Domain\ProductException;
use Modules\Products\Domain\ProductServiceInterface;
use Modules\Products\Infrastructure\HttpClient;
use Modules\Products\Infrastructure\RedisCache;
use function Hyperf\Support\env;

class ProductService implements ProductServiceInterface
{
    private const TTL = 300;

    /**
     * @var CacheBaseInterface
     */
    /**
     * @var HttpClientInterface
     */
    public function __construct(
        protected RedisCache $redis,
        protected HttpClient $httpClient,
    ) {}

    public function getById(int $id)
    {
        $key = 'producId-' . $id;
        $productCache = $this->searchProductOnCache($key);
        if($productCache !== false) {
            return json_decode($productCache, true);
        }
        $product = $this->searchProductAPI($id);
        $this->setProductOnCache($key, $product);
        return $this->buildData($product);
    }

    private function buildData(array $product)
    {
        return [
            'id' => $product['id'],
            'titulo' => $product['title'],
            'imagem' => $product['image'],
            'preco' => $product['price'],
            'review' => $product['review'] ?? '',
        ];
    }

    private function searchProductAPI(int $id): array
    {
        $products = $this->httpClient->get(env('URL_BASE_FAKE_STORE') . "/products/{$id}");
        if (!isset($products)) {
            throw new ProductException('Failed to fetch product from FakeStore API.');
        }
        return $products;
    }

    private function searchProductOnCache(string|int $key): string|bool
    {
        return $this->redis->getCache($key);
    }

    private function setProductOnCache(int|string $key, array $valor): bool
    {
        $product = $this->buildData($valor);
        return $this->redis->setCache($key, json_encode($product), self::TTL);
    }
}
