<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Contracts;

use Tetthys\Permissions\DTO\PermissionPatch;
use Tetthys\Permissions\DTO\PermissionSnapshot;
use Tetthys\Permissions\DTO\PermissionWriteResult;

/**
 * Authority store (DB/Document store/etc.).
 * Must be the source of truth.
 */
interface PermissionRepository
{
    /**
     * Load snapshot from authority.
     * Return null if actor has no record.
     */
    public function load(string $actorId): ?PermissionSnapshot;

    /**
     * Persist a patch to authority.
     * Implementations should:
     * - apply optimistic concurrency if expectedRevision provided
     * - increment revision on change
     */
    public function apply(string $actorId, PermissionPatch $patch): PermissionWriteResult;
}
