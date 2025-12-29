<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Integration\Laravel;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\ServiceProvider;
use Tetthys\Permissions\Core\Contracts\EventBus;
use Tetthys\Permissions\Core\Contracts\PermissionCache;
use Tetthys\Permissions\Core\Contracts\PermissionSerializer;
use Tetthys\Permissions\Core\Contracts\PermissionStore;
use Tetthys\Permissions\Core\Service\PermissionService;
use Tetthys\Permissions\Integration\Laravel\Support\LaravelDbPermissionStore;
use Tetthys\Permissions\Integration\Laravel\Support\LaravelEventBus;
use Tetthys\Permissions\Integration\Laravel\Support\LaravelPermissionCache;
use Tetthys\Permissions\Integration\Laravel\Support\LaravelPermissionSerializer;

final class LaravelPermissionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../../config/tetthys-permissions.php',
            'tetthys-permissions'
        );

        $this->app->singleton(PermissionSerializer::class, LaravelPermissionSerializer::class);

        $this->app->singleton(PermissionCache::class, function ($app): PermissionCache {
            /** @var CacheRepository $cache */
            $cache = $app->make('cache.store');
            return new LaravelPermissionCache($cache);
        });

        $this->app->singleton(EventBus::class, function ($app): EventBus {
            /** @var EventDispatcher $events */
            $events = $app->make('events');
            return new LaravelEventBus($events);
        });

        $this->app->singleton(PermissionStore::class, function ($app): PermissionStore {
            $cfg = (array) config('tetthys-permissions.store', []);

            return new LaravelDbPermissionStore(
                table: (string) ($cfg['table'] ?? 'permission_snapshots'),
                scopeColumn: $cfg['scope_column'] !== null ? (string) $cfg['scope_column'] : null,
                subjectTypeColumn: (string) ($cfg['subject_type_column'] ?? 'subject_type'),
                subjectIdColumn: (string) ($cfg['subject_id_column'] ?? 'subject_id'),
                permissionsColumn: (string) ($cfg['permissions_column'] ?? 'permissions'),
            );
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

        $this->app->singleton('tetthys.permissions', function ($app): PermissionService {
            return $app->make(PermissionService::class);
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../../config/tetthys-permissions.php' => config_path('tetthys-permissions.php'),
        ], 'tetthys-permissions-config');
    }
}
