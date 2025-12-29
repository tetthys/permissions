<?php

declare(strict_types=1);

namespace Tetthys\Permissions\DTO;

final readonly class PermissionWriteResult
{
    public function __construct(
        public PermissionSnapshot $snapshot,
        public bool $changed,
    ) {}
}
