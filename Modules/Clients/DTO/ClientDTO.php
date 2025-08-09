<?php

declare(strict_types=1);

namespace Modules\Clients\DTO;

class ClientDTO
{
    public function toArray(array $dataClient): array
    {
        return [
            'nome' => $dataClient['nome'] ?? '',
            'email' => $dataClient['email'] ?? '',
        ];
    }
}
