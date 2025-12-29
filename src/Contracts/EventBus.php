<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Contracts;

/**
 * Minimal event bus abstraction.
 */
interface EventBus
{
    public function publish(object $event): void;
}
