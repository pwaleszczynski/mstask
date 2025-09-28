<?php

declare(strict_types=1);

namespace App\Core\Warning\Application\Model;

use App\Core\Warning\Domain\WarningObjectType;
use App\Core\Warning\Domain\WarningType;
use Symfony\Component\Uid\Uuid;

final class Warning
{
    public function __construct(
        public readonly Uuid $objectId,
        public readonly WarningObjectType $objectType,
        public readonly WarningType $type,
        public readonly ?Uuid $openWarningId,
    ) {
    }
}
