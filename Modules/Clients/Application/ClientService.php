<?php

declare(strict_types=1);

namespace Modules\Clients\Application;

use Modules\Clients\Domain\ClientException;
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

    public function save(array $data): void
    {
        if(empty($data)){
            throw new ClientException('Empty data for insertion client.', 400);
        }
        $this->clientRepository->persist($data);
    }

    public function update(int $id, array $data): void
    {
        $this->clientRepository->update($id, $data);
    }

    public function delete(int $id): void
    {
        $hasDelete = $this->clientRepository->delete($id);
        if(!isset($hasDelete) || !$hasDelete){
            throw new ClientException("Failure to remove the client.", 503);
        }
    }
}
