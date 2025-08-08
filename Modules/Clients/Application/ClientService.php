<?php

declare(strict_types=1);

namespace Modules\Clients\Application;

use Modules\Clients\Domain\ClientRepositoryInterface;
use Modules\Clients\Domain\ClientServiceInterface;
use Modules\Clients\Infrastructure\ClientRepository;

class ClientService implements ClientServiceInterface
{
    /**
     * @var ClientRepositoryInterface
     */
    public function __construct(protected ClientRepository $clientRepository) {}

    public function getAll(): array
    {
        $clients =  $this->clientRepository->findAll();
        if($clients->isEmpty()) {
            return ['data' => [], 'message' => 'Clients data empty.'];
        }
        return $clients->toArray();
    }

    public function getById(int $id): array
    {
        $client =  $this->clientRepository->findById($id);
        return $client->toArray();
    }
}
