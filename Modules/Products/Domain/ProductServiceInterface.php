<?php

declare(strict_types=1);

namespace Modules\Products\Domain;

interface ProductServiceInterface
{
    public function getById(int $id);
}
