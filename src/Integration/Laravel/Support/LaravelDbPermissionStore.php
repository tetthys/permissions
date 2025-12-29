<?php

declare(strict_types=1);

namespace Tetthys\Permissions\Integration\Laravel\Support;

use Illuminate\Support\Facades\DB;
use Tetthys\Permissions\Core\Contracts\PermissionStore;
use Tetthys\Permissions\Core\Value\SubjectRef;

final class LaravelDbPermissionStore implements PermissionStore
{
    public function __construct(
        private readonly string $table,
        private readonly ?string $scopeColumn,
        private readonly string $subjectTypeColumn,
        private readonly string $subjectIdColumn,
        private readonly string $permissionsColumn,
    ) {}

    public function get(SubjectRef $subject): array
    {
        $q = DB::table($this->table)
            ->where($this->subjectTypeColumn, $subject->type)
            ->where($this->subjectIdColumn, $subject->id);

        if ($this->scopeColumn !== null) {
            $q->where($this->scopeColumn, $subject->scope);
        }

        $row = $q->first([$this->permissionsColumn]);

        if ($row === null) {
            return [];
        }

        $raw = $row->{$this->permissionsColumn} ?? null;

        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            return is_array($decoded) ? array_values($decoded) : [];
        }

        return is_array($raw) ? array_values($raw) : [];
    }

    public function put(SubjectRef $subject, array $permissions): void
    {
        $where = [
            $this->subjectTypeColumn => $subject->type,
            $this->subjectIdColumn => $subject->id,
        ];

        if ($this->scopeColumn !== null) {
            $where[$this->scopeColumn] = $subject->scope;
        }

        $data = [
            $this->permissionsColumn => json_encode($permissions, JSON_UNESCAPED_UNICODE),
        ];

        DB::table($this->table)->updateOrInsert($where, $data);
    }

    public function forget(SubjectRef $subject): void
    {
        $q = DB::table($this->table)
            ->where($this->subjectTypeColumn, $subject->type)
            ->where($this->subjectIdColumn, $subject->id);

        if ($this->scopeColumn !== null) {
            $q->where($this->scopeColumn, $subject->scope);
        }

        $q->delete();
    }
}
