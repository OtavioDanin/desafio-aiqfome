<?php

declare(strict_types=1);

namespace Modules\Products\Application;

interface CacheBaseInterface
{
    public function getCache(string $key);
    public function setCache(string $key, mixed $value, int $ttlInSeconds);
}
