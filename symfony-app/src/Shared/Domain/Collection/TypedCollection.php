<?php

declare(strict_types=1);

namespace App\Shared\Domain\Collection;

abstract class TypedCollection extends Collection
{
    public function __construct(array $items = [])
    {
        foreach ($items as $item) {
            $this->validItem($item);
        }

        parent::__construct($items);
    }

    abstract protected function getType(): string;

    protected function fill(array $items): void {
        foreach ($items as $item) {
            $this->validItem($item);
            parent::add($item);
        }
    }

    public function add(mixed $item): void
    {
        $this->validItem($item);
        parent::add($item);
    }

    public function isItemValid(mixed $item): bool
    {
        $type = $this->getType();

        return $item instanceof $type;
    }

    public function validItem(mixed $item): void
    {
        if (!$this->isItemValid($item)) {
            throw new \InvalidArgumentException('Invalid collection item');
        }
    }
}