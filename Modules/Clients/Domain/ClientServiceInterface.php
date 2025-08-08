<?php

declare(strict_types=1);

namespace Modules\Clients\Domain;

interface ClientServiceInterface
{
    public function getAll();
    public function getById(int $id);
}
