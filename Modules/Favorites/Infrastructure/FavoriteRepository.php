<?php

declare(strict_types=1);

namespace Modules\Favorites\Infrastructure;

use Hyperf\Database\Model\Collection;
use Modules\Favorites\Domain\Favorite;
use Modules\Favorites\Domain\FavoriteRepositoryInterface;

class FavoriteRepository implements FavoriteRepositoryInterface
{
    /**
     * @var Favorite
     */
    public function __construct(protected Favorite $favorite) {}

    public function persist(array $data): Favorite
    {
        return $this->favorite::query()->create($data);
    }

    public function findFavoriteByIdClientAndIdProduct(int $idClient, int $idProduct): Collection
    {
        return $this->favorite::query()
            ->where('client_id', '=', $idClient)
            ->where('product_id', '=', $idProduct)
            ->get();
    }

    public function delete(int $id): ?bool
    {
        $favorite = $this->favorite::query()->findOrFail($id);
        return $favorite->delete();
    }
}
