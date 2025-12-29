<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Contracts;

/**
 * Optional distributed/local lock to reduce stampede and write races.
 */
interface LockProvider
{
    /**
     * Execute callback under a lock.
     * Implementations may use Redis, filesystem, etc.
     *
     * @template T
     * @param callable():T $callback
     * @return T
     */
    public function withLock(string $key, int $ttlSeconds, callable $callback): mixed;
}
