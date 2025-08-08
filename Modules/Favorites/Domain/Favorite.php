<?php

namespace Modules\Favorites\Domain;

use Modules\Clients\Domain\Client;;

/**
 * @property int $cliente_id
 * @property int $produto_id
 */
class Favorite extends Model
{
    protected ?string $table = 'clientes_produtos_favoritos';
    protected array $fillable = ['cliente_id', 'produto_id'];
    public bool $timestamps = false;

    public function client()
    {
        return $this->belongsTo(Client::class, 'cliente_id', 'id');
    }
}
