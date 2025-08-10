<?php

declare(strict_types=1);

namespace Modules\Clients\Domain;

interface ClientServiceInterface
{
    public function getAll();
    public function getById(int $id);
    public function save(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function checkClienteByNomeAndEmail(string $nome, string $email);
}
