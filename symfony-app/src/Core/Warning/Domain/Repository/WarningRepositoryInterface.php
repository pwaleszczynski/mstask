<?php

declare(strict_types=1);

namespace App\Core\Warning\Domain\Repository;

use App\Core\Warning\Domain\Model\WarningCollection;
use App\Shared\Domain\Collection\UuidCollection;

interface WarningRepositoryInterface
{
    public function addBatch(WarningCollection $warnings): void;
    public function delete(UuidCollection $warnings): void;
}