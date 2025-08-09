<?php

declare(strict_types=1);

namespace Modules\Favorites\Domain;

use Modules\Clients\Domain\Client;;

/**
 * @property int $client_id
 * @property int $product_id
 */
class Favorite extends Model
{
    protected ?string $table = 'favorite_products';
    protected array $fillable = ['client_id', 'product_id','titulo', 'imagem', 'preco', 'review', 'data_criacao'];
    public bool $timestamps = false;

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
}
