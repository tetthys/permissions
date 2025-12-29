<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Core\Contracts;

use Tetthys\Permissions\Core\Value\SubjectRef;

interface PermissionStore
{
    /**
     * @return array<int, string>
     */
    public function get(SubjectRef $subject): array;

    /**
     * @param array<int, string> $permissions
     */
    public function put(SubjectRef $subject, array $permissions): void;

    public function forget(SubjectRef $subject): void;
}
