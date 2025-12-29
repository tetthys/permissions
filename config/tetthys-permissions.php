<?php

declare(strict_types=1);

return [
    'ttl_seconds' => 300,
    'cache_prefix' => 'permissions:',

    // Storage settings (dynamic table/columns)
    'store' => [
        'table' => 'permission_snapshots',
        'scope_column' => null,         // e.g. 'org_id' or null
        'subject_type_column' => 'subject_type',
        'subject_id_column' => 'subject_id',
        'permissions_column' => 'permissions',
    ],
];
