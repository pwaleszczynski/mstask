<?php

declare(strict_types=1);

namespace App\Core\Warning\Domain\Model;

use App\Shared\Domain\Collection\TypedCollection;

final class WarningCollection extends TypedCollection
{
    protected function getType(): string
    {
        return Warning::class;
    }
}
