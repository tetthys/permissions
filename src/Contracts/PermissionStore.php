<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Contracts;

interface PermissionStore
{
    /**
     * Load permissions from persistent storage (e.g., DB JSON column).
     *
     * @return array<int, string> List of permission identifiers.
     */
    public function get(string $subjectId): array;

    /**
     * Save permissions to persistent storage (write full snapshot).
     *
     * @param array<int, string> $permissions
     */
    public function put(string $subjectId, array $permissions): void;

    /**
     * Delete permissions snapshot from storage (optional).
     */
    public function forget(string $subjectId): void;
}
