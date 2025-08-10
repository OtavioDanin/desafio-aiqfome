<?php

declare(strict_types=1);

namespace Modules\Clients\Domain;

interface ClientRepositoryInterface
{
    public function findAll();
    public function findById(int $id);
    public function persist(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function findByNomeAndEmail(string $nome, string $email);
}
