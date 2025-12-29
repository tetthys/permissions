<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Core\Event;

final class PermissionChanged
{
    /**
     * @param array<int, string> $permissions
     */
    public function __construct(
        public readonly string $subjectStableId,
        public readonly array $permissions,
        public readonly int $occurredAtUnix,
    ) {}
}
