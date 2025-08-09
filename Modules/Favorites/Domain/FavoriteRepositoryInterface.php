<?php

declare(strict_types=1);

namespace Modules\Favorites\Domain;

interface FavoriteRepositoryInterface
{
    public function persist(array $data);
}