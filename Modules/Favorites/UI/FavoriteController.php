<?php

declare(strict_types=1);

namespace Modules\Favorites\UI;

use Hyperf\Database\Exception\QueryException;
use Hyperf\Database\Model\ModelNotFoundException;
use Modules\Favorites\Application\FavoriteService;
use Modules\Favorites\Domain\FavoriteServiceInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Validation\ValidationException;
use Modules\Favorites\Application\FavoriteRequestValidation;
use Modules\Favorites\Application\ValidationRules;
use Modules\Favorites\Domain\FavoriteException;
use Modules\Favorites\DTO\FavoriteDTO;
use Modules\Products\Application\ProductService;
use Modules\Products\Domain\ProductServiceInterface;
use Throwable;

class FavoriteController extends AbstractController
{
    /**
     * @var ValidationRules
     */
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
        protected ValidationRules $validation,
    ) {}

    public function store(FavoriteRequestValidation $request, ResponseInterface $response): \Psr\Http\Message\ResponseInterface
    {
        try {
            $request->validated();
            $this->validation->checkProductDuplicate($request->input('client_id'),$request->input('product_id'));
            $product = $this->productService->getById($request->input('product_id'));
            $dataClient = $this->dto->toArray($request->all()+$product);
            $this->favoriteService->save($dataClient);
            return $response
                ->json(['data' => $dataClient, 'message' => 'Favorite save Success.'])
                ->withStatus(201);
        } catch (ValidationException $vaException) {
            return $response
                ->json(['message' => $vaException->getMessage()])
                ->withStatus(400);
        } catch (FavoriteException $favEx) {
            return $response
                ->json(['message' => $favEx->getMessage()])
                ->withStatus($favEx->getCode());
        } catch (Throwable $thEx) {
            return $response
                ->json(['message' => $thEx->getMessage()])
                ->withStatus(500);
        }
    }

    public function destroy(ResponseInterface $response, int $id): \Psr\Http\Message\ResponseInterface
    {
        try {
            $this->favoriteService->delete($id);
            return $response
                ->json([])
                ->withStatus(204);
        } catch(FavoriteException $favoriteEx) {
            return $response
                ->json(['message' => $favoriteEx->getMessage()])
                ->withStatus($favoriteEx->getCode());
        } catch (ModelNotFoundException) {
            return $response
                ->json(['message' => 'Favorite not found for Delete.'])
                ->withStatus(404);
        } catch (QueryException) {
            return $response
                ->json(['message' => 'Failed to delete the Client.'])
                ->withStatus(503);
        } catch (Throwable) {
            return $response
                ->json(['message' => 'Ocorreu um erro fora do previsto, tente novamente em alguns instantes.'])
                ->withStatus(500);
        }
    }
}
