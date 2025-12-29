<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Core\Service;

use Closure;
use Tetthys\Permissions\Core\Contracts\EventBus;
use Tetthys\Permissions\Core\Contracts\PermissionCache;
use Tetthys\Permissions\Core\Contracts\PermissionSerializer;
use Tetthys\Permissions\Core\Contracts\PermissionStore;
use Tetthys\Permissions\Core\Event\PermissionChanged;
use Tetthys\Permissions\Core\Value\PermissionSet;
use Tetthys\Permissions\Core\Value\SubjectRef;

final class PermissionService
{
    private Closure $nowUnix;

    public function __construct(
        private readonly PermissionStore $store,
        private readonly PermissionCache $cache,
        private readonly PermissionSerializer $serializer,
        private readonly EventBus $events,
        private readonly int $ttlSeconds = 300,
        private readonly string $cachePrefix = 'permissions:',
        ?Closure $nowUnix = null,
    ) {
        $this->nowUnix = $nowUnix ?? static fn(): int => time();
    }

    public function get(SubjectRef $subject): PermissionSet
    {
        $key = $subject->cacheKey($this->cachePrefix);

        $cached = $this->cache->get($key);
        if ($cached !== null) {
            return new PermissionSet($cached);
        }

        $raw = $this->store->get($subject);
        $normalized = $this->serializer->normalize($raw);

        $this->cache->put($key, $normalized, $this->ttlSeconds);

        return new PermissionSet($normalized);
    }

    /**
     * Replace whole permission snapshot.
     *
     * @param array<int, string> $permissions
     */
    public function put(SubjectRef $subject, array $permissions): PermissionSet
    {
        $normalized = $this->serializer->normalize($permissions);

        $this->store->put($subject, $normalized);
        $this->cache->put($subject->cacheKey($this->cachePrefix), $normalized, $this->ttlSeconds);

        $this->events->publish(new PermissionChanged(
            subjectStableId: $subject->toStableId(),
            permissions: $normalized,
            occurredAtUnix: ($this->nowUnix)(),
        ));

        return new PermissionSet($normalized);
    }

    public function forget(SubjectRef $subject): void
    {
        $this->store->forget($subject);
        $this->cache->forget($subject->cacheKey($this->cachePrefix));

        $this->events->publish(new PermissionChanged(
            subjectStableId: $subject->toStableId(),
            permissions: [],
            occurredAtUnix: ($this->nowUnix)(),
        ));
    }
}
