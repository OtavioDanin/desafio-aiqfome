<?php

declare(strict_types=1);

namespace Modules\Favorites\DTO;

class FavoriteDTO
{
    public function toArray(array $data): array
    {
        return [
            'client_id' => $data['client_id'],
            'product_id' => $data['product_id'],
            'titulo' => $data['titulo'],
            'imagem' => $data['imagem'],
            'preco' => (string) $data['preco'],
            'review' => $data['review'],
            'data_criacao' => date("Y-m-d H:i:s"),
        ];
    }
}
