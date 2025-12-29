<?php

declare(strict_types=1);

namespace Tetthys\Permissions\DTO;

/**
 * Cache key builder for a permission snapshot.
 */
final readonly class CacheKey
{
    public function __construct(
        public string $namespace,
        public string $actorId,
    ) {}

    public function value(): string
    {
        return $this->namespace . ':permissions:' . $this->actorId;
    }
}
