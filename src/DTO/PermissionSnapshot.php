<?php

declare(strict_types=1);

namespace Tetthys\Permissions\DTO;

/**
 * A versioned snapshot of permissions for an actor (user/staff/etc.).
 * Revision can be used for optimistic concurrency and cache coherence.
 */
final readonly class PermissionSnapshot
{
    public function __construct(
        public string $actorId,
        public PermissionSet $permissions,
        public int $revision,
        public ?\DateTimeImmutable $updatedAt = null,
    ) {}
}
