<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Integrations\Laravel\Support;

use Illuminate\Support\Facades\DB;
use Tetthys\Permissions\Contracts\PermissionStore;

final class LaravelPermissionStore implements PermissionStore
{
    public function __construct(
        private string $table,
        private string $subjectIdColumn = 'subject_id',
        private string $permissionsColumn = 'permissions',
    ) {}

    public function get(string $subjectId): array
    {
        $row = DB::table($this->table)
            ->where($this->subjectIdColumn, $subjectId)
            ->first([$this->permissionsColumn]);

        if ($row === null) {
            return [];
        }

        $raw = $row->{$this->permissionsColumn} ?? null;

        // If DB driver returns JSON as string, decode it.
        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            return is_array($decoded) ? array_values($decoded) : [];
        }

        // Some drivers may return array directly
        return is_array($raw) ? array_values($raw) : [];
    }

    public function put(string $subjectId, array $permissions): void
    {
        // Write full snapshot (upsert)
        DB::table($this->table)->updateOrInsert(
            [$this->subjectIdColumn => $subjectId],
            [$this->permissionsColumn => json_encode($permissions, JSON_UNESCAPED_UNICODE)]
        );
    }

    public function forget(string $subjectId): void
    {
        DB::table($this->table)
            ->where($this->subjectIdColumn, $subjectId)
            ->delete();
    }
}
