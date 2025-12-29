# Tetthys Permissions

A lightweight, framework-agnostic **string-based permission engine** with
**read-through / write-through caching**, designed for Laravel integration but usable without it.

Permissions are stored as **immutable snapshots (JSON arrays)** and identified by a flexible
**Subject reference (type / id / optional scope)**.

---

## Core Concepts

### 1. SubjectRef (Who owns permissions?)

Every permission set belongs to a **Subject**:

- `type` — logical category (e.g. `user`, `staff`, `role`)
- `id` — identifier (ULID, UUID, string)
- `scope` — optional context (e.g. organization, tenant)

```text
[scope]:[type]:[id]
````

Examples:

* `user:01HV...`
* `staff:01HV...`
* `org:1:user:01HV...`

---

### 2. Permissions

Permissions are **plain strings**, representing domain actions:

```text
staff.read
staff.update
orders.create
orders.refund
```

No table names, no columns, no booleans in business logic.

---

### 3. Pipeline (Read / Write Flow)

#### Read (read-through cache)

```
get(subject)
 ├─ check cache
 │   └─ hit → return
 └─ miss
     ├─ load snapshot from store (DB / other)
     ├─ normalize
     ├─ write to cache
     └─ return PermissionSet
```

#### Write (write-through cache)

```
put(subject, permissions)
 ├─ normalize
 ├─ persist snapshot to store
 ├─ update cache
 └─ emit PermissionChanged event
```

---

## Installation (Laravel)

```bash
composer require tetthys/permissions
```

Publish configuration (optional):

```bash
php artisan vendor:publish --tag=tetthys-permissions-config
```

---

## Database Setup

Minimal snapshot table:

```php
Schema::create('permission_snapshots', function (Blueprint $table) {
    $table->id();
    $table->string('subject_type');
    $table->string('subject_id');
    $table->json('permissions');

    // Optional scope (multi-tenant)
    // $table->string('org_id')->nullable();

    $table->unique(['subject_type', 'subject_id']);
});
```

---

## Basic Usage

### Create a Subject

```php
use Tetthys\Permissions\Core\Value\SubjectRef;

$subject = new SubjectRef(
    type: 'user',
    id: (string) $user->id
);
```

---

### Store Permissions (Replace Snapshot)

```php
use Permissions;

Permissions::put($subject, [
    'staff.read',
    'staff.update',
]);
```

---

### Read & Check Permissions

```php
$set = Permissions::get($subject);

if (! $set->has('staff.update')) {
    abort(403);
}
```

---

### Remove Permissions

```php
Permissions::forget($subject);
```

---

## Using the Facade

The Laravel Facade is automatically registered.

```php
use Permissions;
use Tetthys\Permissions\Core\Value\SubjectRef;

Permissions::get($subject);
Permissions::put($subject, [...]);
Permissions::forget($subject);
```

---

## Optional Subject Helper

For convenience, you may use the helper:

```php
use Tetthys\Permissions\Integration\Laravel\Support\Subject;

Permissions::put(
    Subject::user((string) $user->id),
    ['orders.read']
);
```

---

## Events

Every write emits a domain event:

```php
Tetthys\Permissions\Core\Event\PermissionChanged
```

Example listener:

```php
Event::listen(PermissionChanged::class, function (PermissionChanged $event) {
    // $event->subjectStableId
    // $event->permissions
    // $event->occurredAtUnix
});
```

Use cases:

* Audit logging
* Session invalidation
* External sync

---

## Configuration

`config/tetthys-permissions.php`

```php
return [
    'ttl_seconds' => 300,
    'cache_prefix' => 'permissions:',

    'store' => [
        'table' => 'permission_snapshots',
        'subject_type_column' => 'subject_type',
        'subject_id_column' => 'subject_id',
        'permissions_column' => 'permissions',
        'scope_column' => null,
    ],
];
```

---

## Design Goals

* Framework-agnostic core
* No boolean permission columns
* Immutable permission snapshots
* Cache-first reads
* Explicit, observable writes
* Easy migration from legacy `can_*` flags

---

## Typical Migration Strategy

1. Read legacy flags (`can_*`)
2. Convert to permission strings
3. Store snapshot via `Permissions::put()`
4. Switch all checks to `PermissionSet::has()`
5. Remove legacy columns

---

## License

MIT
