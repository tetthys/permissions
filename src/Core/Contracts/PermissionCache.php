<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Core\Contracts;

interface PermissionCache
{
    /**
     * @return array<int, string>|null Null means cache miss.
     */
    public function get(string $key): ?array;

    /**
     * @param array<int, string> $permissions
     */
    public function put(string $key, array $permissions, int $ttlSeconds): void;

    public function forget(string $key): void;
}
