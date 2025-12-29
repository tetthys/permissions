<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Contracts;

interface EventBus
{
    /**
     * Publish an event to the outside world (optional).
     * Implementation can be noop if not needed.
     */
    public function publish(object $event): void;
}
