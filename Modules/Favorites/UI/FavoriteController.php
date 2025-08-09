<?php

declare(strict_types=1);

namespace Modules\Favorites\UI;

use Modules\Favorites\Application\FavoriteService;
use Modules\Favorites\Domain\FavoriteServiceInterface;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Modules\Favorites\DTO\FavoriteDTO;
use Modules\Products\Application\ProductService;
use Modules\Products\Domain\ProductServiceInterface;
use Throwable;

class FavoriteController extends AbstractController
{
    /**
     * @var ProductServiceInterface
     */
    /**
     * @var FavoriteServiceInterface
     */
    public function __construct(
        protected FavoriteService $favoriteService,
        protected ProductService $productService,
        protected FavoriteDTO $dto,
    ) {}

    public function store(RequestInterface $request, ResponseInterface $response): \Psr\Http\Message\ResponseInterface
    {
        try {
            $product = $this->productService->getById($request->input('product_id'));
            $dataClient = $this->dto->toArray($request->all()+$product);
            $this->favoriteService->save($dataClient);
            return $response
                ->json(['data' => $dataClient, 'message' => 'Favorite save Success.'])
                ->withStatus(201);
        } catch (Throwable $thEx) {
            return $response
                ->json(['message' => $thEx->getMessage()])
                ->withStatus(500);
        }
    }
}
