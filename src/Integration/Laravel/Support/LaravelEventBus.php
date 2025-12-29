<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Integration\Laravel\Support;

use Illuminate\Contracts\Events\Dispatcher;
use Tetthys\Permissions\Core\Contracts\EventBus;

final class LaravelEventBus implements EventBus
{
    public function __construct(private readonly Dispatcher $events) {}

    public function publish(object $event): void
    {
        $this->events->dispatch($event);
    }
}
