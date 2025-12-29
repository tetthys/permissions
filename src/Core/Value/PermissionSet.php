<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Core\Value;

final class PermissionSet
{
    /**
     * @param array<int, string> $items
     */
    public function __construct(private readonly array $items) {}

    /**
     * @return array<int, string>
     */
    public function all(): array
    {
        return $this->items;
    }

    public function has(string $permission): bool
    {
        return in_array($permission, $this->items, true);
    }
}
