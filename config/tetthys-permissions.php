<?php

declare(strict_types=1);

return [
    'ttl_seconds' => 300,
    'cache_prefix' => 'permissions:',

    'store' => [
        // Table storing permission snapshots
        'table' => 'permission_snapshots',

        // Subject identifier column
        'subject_id_column' => 'subject_id',

        // JSON column containing array<int, string>
        'permissions_column' => 'permissions',
    ],
];
