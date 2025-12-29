<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Contracts;

interface PermissionSerializer
{
    /**
     * Normalize raw permissions for storage/cache:
     * - remove duplicates
     * - trim
     * - stable sort (optional)
     *
     * @param array<int, string> $permissions
     * @return array<int, string>
     */
    public function normalize(array $permissions): array;
}
