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

    public function findById(int $id): Client
    {
        return $this->client::query()->findOrFail($id, ['id', 'nome', 'email']);
    }

    public function persist(array $data): Client
    {
        return $this->client::query()->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $cliente = $this->client::query()->findOrFail($id);
        return $cliente->updateOrFail($data);
    }

    public function delete(int $id): ?bool
    {
        $cliente = $this->client::query()->findOrFail($id);
        return $cliente->delete();
    }

    public function findByNomeAndEmail(string $nome, string $email): Collection
    {
        return $this->client->query()
            ->where('nome', '=', $nome)
            ->where('email', '=', $email)
            ->get();
    }
}
