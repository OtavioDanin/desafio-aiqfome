<?php

declare(strict_types=1);

namespace Modules\Clients\Infrastructure;

use Hyperf\Collection\Collection;
use Modules\Clients\Domain\Client;
use Modules\Clients\Domain\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface
{
    /**
     * @var Client
     */
    public function __construct(protected Client $client) {}

    public function findAll(): Collection
    {
        return $this->client::query()->get(['id', 'nome', 'email']);
    }

    public function findById(int $id): Collection
    {
        return $this->client::query()->findOrFail($id, ['id', 'nome', 'email']);
    }
}
