<?php

declare(strict_types=1);

namespace App\Shared\Domain\Collection;

use Symfony\Component\Uid\Uuid;

final class UuidCollection extends TypedCollection
{
    protected function getType(): string
    {
        return Uuid::class;
    }
}
