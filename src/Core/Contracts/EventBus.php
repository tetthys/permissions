<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Core\Contracts;

interface EventBus
{
    public function publish(object $event): void;
}
