<?php

declare(strict_types=1);

namespace Modules\Favorites\Domain;

interface FavoriteServiceInterface
{
    public function save(array $data);
}
