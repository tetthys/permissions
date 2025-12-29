<?php

declare(strict_types=1);

namespace Tetthys\Permissions\DTO;

/**
 * Immutable set of permission keys.
 */
final readonly class PermissionSet
{
    /** @var array<string, true> */
    private array $map;

    /**
     * @param list<string> $keys
     */
    public function __construct(array $keys = [])
    {
        $m = [];
        foreach ($keys as $k) {
            $k = trim($k);
            if ($k === '') {
                continue;
            }
            $m[$k] = true;
        }
        $this->map = $m;
    }

    /**
     * @return list<string>
     */
    public function keys(): array
    {
        return array_values(array_keys($this->map));
    }

    public function has(string $key): bool
    {
        return isset($this->map[$key]);
    }

    public function isEmpty(): bool
    {
        return $this->map === [];
    }

    public function with(string ...$keys): self
    {
        $m = $this->map;
        foreach ($keys as $k) {
            $k = trim($k);
            if ($k !== '') {
                $m[$k] = true;
            }
        }
        return new self(array_keys($m));
    }

    public function without(string ...$keys): self
    {
        $m = $this->map;
        foreach ($keys as $k) {
            unset($m[$k]);
        }
        return new self(array_keys($m));
    }
}
