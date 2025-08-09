<?php

declare(strict_types=1);

namespace Modules\Clients\Domain;

use Composer\Semver\Interval;

interface ClientRepositoryInterface
{
    public function findAll();
    public function findById(int $id);
    public function persist(array $data);
    public function update(int $id, array $data);
}
