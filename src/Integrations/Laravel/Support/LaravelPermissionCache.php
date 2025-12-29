<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Integrations\Laravel\Support;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Tetthys\Permissions\Contracts\PermissionCache;

final class LaravelPermissionCache implements PermissionCache
{
    public function __construct(private CacheRepository $cache) {}

    public function get(string $key): ?array
    {
        $val = $this->cache->get($key);

        // Cache miss -> return null
        if ($val === null) {
            return null;
        }

        // Expect array<int, string>
        return is_array($val) ? $val : null;
    }

    public function put(string $key, array $permissions, int $ttlSeconds): void
    {
        // Store as plain array, TTL in seconds
        $this->cache->put($key, $permissions, $ttlSeconds);
    }

    public function forget(string $key): void
    {
        $this->cache->forget($key);
    }
}
