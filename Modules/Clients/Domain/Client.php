<?php

declare(strict_types=1);

namespace Modules\Clients\Domain;

use Modules\Favorites\Domain\Favorite;

/**
 * @property int $id
 * @property string $nome
 * @property string $email
 */

class Client extends Model
{
    protected ?string $table = 'clients';
    protected array $fillable = ['nome', 'email'];

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'cliente_id', 'id');
    }
}
