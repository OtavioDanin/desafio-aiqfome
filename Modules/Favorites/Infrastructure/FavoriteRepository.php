<?php

declare(strict_types=1);

namespace Modules\Favorites\Infrastructure;

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
}
