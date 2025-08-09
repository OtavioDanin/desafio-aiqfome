<?php

declare(strict_types=1);

namespace Modules\Products\Application;

use Modules\Products\Domain\ProductException;
use Modules\Products\Domain\ProductServiceInterface;
use Modules\Products\Infrastructure\HttpClient;

class ProductService implements ProductServiceInterface
{
    private const BASE_URL = 'https://fakestoreapi.com';
    /**
     * @var HttpClientInterface
     */
    public function __construct(protected HttpClient $httpClient) {}

    public function getById(int $id)
    {
        $products = $this->httpClient->get(self::BASE_URL . "/products/{$id}");
        if (!isset($products)) {
            throw new ProductException('Failed to fetch product from FakeStore API.');
        }
        return [
            'id' => $products['id'],
            'titulo' => $products['title'],
            'imagem' => $products['image'],
            'preco' => $products['price'],
            'preco' => $products['price'],
            'review' => $products['review'] ?? '',
        ];
    }
}
