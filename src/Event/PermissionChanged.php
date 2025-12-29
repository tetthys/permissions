<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Event;

/**
 * Emitted after permissions are persisted and cache is updated.
 */
final class PermissionChanged
{
    /**
     * @param array<int, string> $permissions
     */
    public function __construct(
        public readonly string $subjectId,
        public readonly array $permissions,
        public readonly int $occurredAtUnix,
    ) {
    }
}
