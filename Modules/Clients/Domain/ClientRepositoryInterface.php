<?php

declare(strict_types=1);

namespace Modules\Clients\Domain;

interface ClientRepositoryInterface
{
    public function findAll();
    public function findById(int $id);
}
