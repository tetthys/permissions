<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Integrations\Laravel\Support;

use Illuminate\Contracts\Events\Dispatcher;
use Tetthys\Permissions\Contracts\EventBus;

final class LaravelEventBus implements EventBus
{
    public function __construct(private Dispatcher $events) {}

    public function publish(object $event): void
    {
        // Fire-and-forget domain event
        $this->events->dispatch($event);
    }
}
