<?php

declare(strict_types=1);

namespace App\Core\Warning\Application\Model;

use App\Core\Warning\Domain\WarningType;

final class GeneratorOutput
{
    public function __construct(
        public readonly WarningType $type,
        public readonly int $new,
        public readonly int $sustained,
        public readonly int $closed,
    ) {
    }
}
