<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Value;

/**
 * Immutable permission set value object.
 */
final class PermissionSet
{
    /**
     * @param array<int, string> $items
     */
    public function __construct(private array $items)
    {
    }

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
