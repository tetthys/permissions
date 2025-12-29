<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Contracts;

use Tetthys\Permissions\DTO\PermissionPatch;
use Tetthys\Permissions\DTO\PermissionSnapshot;
use Tetthys\Permissions\DTO\PermissionWriteResult;

/**
 * Orchestrates cache-aside reads and write-through updates (DB then cache).
 */
interface PermissionService
{
    /**
     * Get snapshot (prefer cache).
     */
    public function get(string $actorId): PermissionSnapshot;

    /**
     * Apply patch to authority and then update cache accordingly.
     */
    public function update(string $actorId, PermissionPatch $patch): PermissionWriteResult;

    /**
     * Explicit cache invalidation (optional).
     */
    public function invalidate(string $actorId): void;
}
