<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Contracts;

/**
 * Validates permission keys (format + allowlist/registry if desired).
 */
interface PermissionKeyValidator
{
    public function assertValid(string $key): void;

    /**
     * @param list<string> $keys
     */
    public function assertAllValid(array $keys): void;
}
