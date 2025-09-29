<?php

declare(strict_types=1);

namespace App\Shared\Domain\Collection;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

abstract class Collection implements IteratorAggregate
{
    public function __construct(
        private array $items,
    ) {
    }

    protected function clear(): void
    {
        $this->items = [];
    }

    public static function createEmpty(): static
    {
        return new static([]);
    }

    public function add(mixed $item): void
    {
        $this->items[] = $item;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}
