<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Integrations\Laravel;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\ServiceProvider;
use Tetthys\Permissions\Contracts\EventBus;
use Tetthys\Permissions\Contracts\PermissionCache;
use Tetthys\Permissions\Contracts\PermissionSerializer;
use Tetthys\Permissions\Contracts\PermissionStore;
use Tetthys\Permissions\Integrations\Laravel\Support\LaravelEventBus;
use Tetthys\Permissions\Integrations\Laravel\Support\LaravelPermissionCache;
use Tetthys\Permissions\Integrations\Laravel\Support\LaravelPermissionSerializer;
use Tetthys\Permissions\Integrations\Laravel\Support\LaravelPermissionStore;
use Tetthys\Permissions\Service\PermissionService;

final class LaravelPermissionsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../../../config/tetthys-permissions.php',
            'tetthys-permissions'
        );

        $this->app->singleton(PermissionSerializer::class, LaravelPermissionSerializer::class);

        $this->app->singleton(PermissionCache::class, function ($app): PermissionCache {
            /** @var CacheRepository $cache */
            $cache = $app->make('cache.store');

            return new LaravelPermissionCache($cache);
        });

        $this->app->singleton(PermissionStore::class, function ($app): PermissionStore {
            $cfg = (array) config('tetthys-permissions.store', []);

            return new LaravelPermissionStore(
                table: (string) ($cfg['table'] ?? 'permission_snapshots'),
                subjectIdColumn: (string) ($cfg['subject_id_column'] ?? 'subject_id'),
                permissionsColumn: (string) ($cfg['permissions_column'] ?? 'permissions'),
            );
        });

        $this->app->singleton(EventBus::class, function ($app): EventBus {
            /** @var EventDispatcher $events */
            $events = $app->make('events');

            return new LaravelEventBus($events);
        });

        $this->app->singleton(PermissionService::class, function ($app): PermissionService {
            $cfg = (array) config('tetthys-permissions', []);

            return new PermissionService(
                store: $app->make(PermissionStore::class),
                cache: $app->make(PermissionCache::class),
                serializer: $app->make(PermissionSerializer::class),
                events: $app->make(EventBus::class),
                ttlSeconds: (int) ($cfg['ttl_seconds'] ?? 300),
                cachePrefix: (string) ($cfg['cache_prefix'] ?? 'permissions:'),
            );
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../../../config/tetthys-permissions.php' => config_path('tetthys-permissions.php'),
        ], 'tetthys-permissions-config');
    }
}
