<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Events;

final readonly class PermissionsCacheInvalidated
{
    public function __construct(
        public string $actorId,
    ) {}
}
