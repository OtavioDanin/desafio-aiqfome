<?php

declare(strict_types=1);

namespace Modules\Favorites\Domain;

use Modules\Clients\Domain\Client;;

/**
 * @property int $cliente_id
 * @property int $produto_id
 */
class Favorite extends Model
{
    protected ?string $table = 'favorite_products';
    protected array $fillable = ['cliente_id', 'produto_id', 'data_adicao'];
    public bool $timestamps = false;

    public function client()
    {
        return $this->belongsTo(Client::class, 'cliente_id', 'id');
    }
}
