<?php

declare(strict_types=1);

namespace Modules\Favorites\Application;

use Modules\Favorites\Domain\FavoriteRepositoryInterface;
use Modules\Favorites\Domain\FavoriteServiceInterface;
use Modules\Favorites\Infrastructure\FavoriteRepository;

class FavoriteService implements FavoriteServiceInterface
{
    /**
     * @var FavoriteRepositoryInterface
     */
    public function __construct(protected FavoriteRepository $favoriteRepository) {}

    public function save(array $data): void
    {
        $this->favoriteRepository->persist($data);
    }
}
