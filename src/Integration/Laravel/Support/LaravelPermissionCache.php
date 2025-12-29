<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Integration\Laravel\Support;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Tetthys\Permissions\Core\Contracts\PermissionCache;

final class LaravelPermissionCache implements PermissionCache
{
    public function __construct(private readonly CacheRepository $cache) {}

    public function get(string $key): ?array
    {
        $val = $this->cache->get($key);
        if ($val === null) {
            return null;
        }

        return is_array($val) ? $val : null;
    }

    public function put(string $key, array $permissions, int $ttlSeconds): void
    {
        $this->cache->put($key, $permissions, $ttlSeconds);
    }

    public function forget(string $key): void
    {
        $this->cache->forget($key);
    }
}
