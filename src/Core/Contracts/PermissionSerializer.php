<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Core\Contracts;

interface PermissionSerializer
{
    /**
     * @param array<int, string> $permissions
     * @return array<int, string>
     */
    public function normalize(array $permissions): array;
}
