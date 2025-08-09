<?php

declare(strict_types=1);

namespace Modules\Products\Infrastructure;

use Hyperf\Redis\Redis;
use Modules\Products\Application\CacheBaseInterface;

class RedisCache extends Redis implements CacheBaseInterface
{
    /**
     * @var Redis
     */
    public function __construct(protected Redis $redis) {}

    public function getCache(string $key): string|false
    {
        return $this->redis->get($key);
    }

    public function setCache(string|int $key, mixed $value, int $ttlInSeconds = 3600): bool
    {
        return $this->redis->set($key, $value, $ttlInSeconds);
    }
}
