<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Core\Value;

final class SubjectRef
{
    public function __construct(
        public readonly string $type,   // e.g. "user", "staff", "role"
        public readonly string $id,     // e.g. UUID
        public readonly ?string $scope = null, // e.g. "org:1" or null
    ) {}

    public function toStableId(): string
    {
        // Stable identifier used in events/logging (independent of DB table names)
        return ($this->scope ? $this->scope . ':' : '') . $this->type . ':' . $this->id;
    }

    public function cacheKey(string $prefix): string
    {
        return $prefix . $this->toStableId();
    }
}
