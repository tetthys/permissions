<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Contracts;

interface Clock
{
    public function now(): \DateTimeImmutable;
}
