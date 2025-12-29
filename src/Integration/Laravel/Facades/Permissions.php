<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Integration\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Tetthys\Permissions\Core\Value\PermissionSet get(\Tetthys\Permissions\Core\Value\SubjectRef $subject)
 * @method static \Tetthys\Permissions\Core\Value\PermissionSet put(\Tetthys\Permissions\Core\Value\SubjectRef $subject, array $permissions)
 * @method static void forget(\Tetthys\Permissions\Core\Value\SubjectRef $subject)
 */
final class Permissions extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        // Bind this key in the service provider
        return 'tetthys.permissions';
    }
}
