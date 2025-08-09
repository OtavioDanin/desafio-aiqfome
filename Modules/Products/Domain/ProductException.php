<?php

declare(strict_types=1);

namespace Modules\Products\Domain;

use Exception;
use Throwable;

class ProductException extends Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable|null $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
