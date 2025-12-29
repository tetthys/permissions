<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Integration\Laravel\Support;

use Tetthys\Permissions\Core\Contracts\PermissionSerializer;

final class LaravelPermissionSerializer implements PermissionSerializer
{
    public function normalize(array $permissions): array
    {
        $items = array_map(static fn($p) => trim((string) $p), $permissions);
        $items = array_values(array_filter($items, static fn($p) => $p !== ''));

        $items = array_values(array_unique($items));
        sort($items);

        return $items;
    }
}
