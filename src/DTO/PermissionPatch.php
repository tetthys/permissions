<?php

declare(strict_types=1);

namespace Tetthys\Permissions\DTO;

/**
 * Represents an intended permission update.
 * - grant: add keys
 * - revoke: remove keys
 * - replace: full replacement (optional)
 */
final readonly class PermissionPatch
{
    public function __construct(
        public PermissionSet $grant = new PermissionSet([]),
        public PermissionSet $revoke = new PermissionSet([]),
        public ?PermissionSet $replace = null,
        public ?int $expectedRevision = null,
    ) {}

    public static function grant(string ...$keys): self
    {
        return new self(grant: new PermissionSet($keys));
    }

    public static function revoke(string ...$keys): self
    {
        return new self(revoke: new PermissionSet($keys));
    }

    public static function replace(PermissionSet $set, ?int $expectedRevision = null): self
    {
        return new self(replace: $set, expectedRevision: $expectedRevision);
    }
}
