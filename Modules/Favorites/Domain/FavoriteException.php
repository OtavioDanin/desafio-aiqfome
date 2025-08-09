<?php

declare(strict_types=1);

namespace Modules\Favorites\Domain;

use Exception;
use Throwable;

class FavoriteException extends Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable|null $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
