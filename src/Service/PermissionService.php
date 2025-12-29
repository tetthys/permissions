<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Service;

use Closure;
use Tetthys\Permissions\Contracts\EventBus;
use Tetthys\Permissions\Contracts\PermissionCache;
use Tetthys\Permissions\Contracts\PermissionSerializer;
use Tetthys\Permissions\Contracts\PermissionStore;
use Tetthys\Permissions\Event\PermissionChanged;
use Tetthys\Permissions\Value\PermissionSet;

/**
 * Read-through + write-through permission service.
 */
final class PermissionService
{
    private Closure $nowUnix;

    public function __construct(
        private PermissionStore $store,
        private PermissionCache $cache,
        private PermissionSerializer $serializer,
        private EventBus $events,
        private int $ttlSeconds = 300,
        private string $cachePrefix = 'permissions:',
        ?Closure $nowUnix = null,
    ) {
        // Default clock
        $this->nowUnix = $nowUnix ?? static fn(): int => time();
    }

    public function get(string $subjectId): PermissionSet
    {
        $key = $this->cacheKey($subjectId);

        $cached = $this->cache->get($key);
        if ($cached !== null) {
            return new PermissionSet($cached);
        }

        $raw = $this->store->get($subjectId);
        $normalized = $this->serializer->normalize($raw);

        // Fill cache on miss
        $this->cache->put($key, $normalized, $this->ttlSeconds);

        return new PermissionSet($normalized);
    }

    /**
     * Replace whole permission snapshot.
     *
     * @param array<int, string> $permissions
     */
    public function put(string $subjectId, array $permissions): PermissionSet
    {
        $normalized = $this->serializer->normalize($permissions);

        // Persist first, then cache
        $this->store->put($subjectId, $normalized);
        $this->cache->put($this->cacheKey($subjectId), $normalized, $this->ttlSeconds);

        $this->events->publish(new PermissionChanged(
            subjectId: $subjectId,
            permissions: $normalized,
            occurredAtUnix: ($this->nowUnix)(),
        ));

        return new PermissionSet($normalized);
    }

    public function forget(string $subjectId): void
    {
        $this->store->forget($subjectId);
        $this->cache->forget($this->cacheKey($subjectId));

        $this->events->publish(new PermissionChanged(
            subjectId: $subjectId,
            permissions: [],
            occurredAtUnix: ($this->nowUnix)(),
        ));
    }

    private function cacheKey(string $subjectId): string
    {
        return $this->cachePrefix . $subjectId;
    }
}
