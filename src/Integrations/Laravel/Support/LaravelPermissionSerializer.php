<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Integrations\Laravel\Support;

use Tetthys\Permissions\Contracts\PermissionSerializer;

final class LaravelPermissionSerializer implements PermissionSerializer
{
    public function normalize(array $permissions): array
    {
        // Trim + filter empty + unique + stable sort
        $items = array_map(static fn($p) => trim((string) $p), $permissions);
        $items = array_values(array_filter($items, static fn($p) => $p !== ''));

        $items = array_values(array_unique($items));

        sort($items); // Optional stable ordering for cache/storage predictability

        return $items;
    }
}
