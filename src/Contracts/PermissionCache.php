<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Contracts;

use Tetthys\Permissions\DTO\PermissionSnapshot;

/**
 * High-performance cache for permission snapshots.
 */
interface PermissionCache
{
    public function get(string $actorId): ?PermissionSnapshot;

    /**
     * Store snapshot with TTL seconds (null = cache default).
     */
    public function put(PermissionSnapshot $snapshot, ?int $ttlSeconds = null): void;

    public function forget(string $actorId): void;
}
