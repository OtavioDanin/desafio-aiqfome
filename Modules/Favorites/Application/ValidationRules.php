<?php

declare(strict_types=1);

namespace Modules\Favorites\Application;

use Modules\Favorites\Domain\FavoriteException;
use Modules\Favorites\Infrastructure\FavoriteRepository;

class ValidationRules
{
    /**
     * @var FavoriteRepositoryInterface
     */
    public function __construct(protected FavoriteRepository $favoriteRepository) {}

    public function checkProductDuplicate(int $idCliente, $idProduct): void
    {
        $favorite = $this->favoriteRepository->findFavoriteByIdClientAndIdProduct($idCliente, $idProduct);
        if(!empty($favorite->toArray())) {
            throw new FavoriteException('Operation invalid. Product has been added to favorites list', 403);
        }
    }
}
