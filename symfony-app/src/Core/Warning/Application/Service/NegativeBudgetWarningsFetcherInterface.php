<?php

declare(strict_types=1);

namespace App\Core\Warning\Application\Service;

use App\Core\Warning\Application\Model\WarningCollection;
use App\Shared\Domain\Collection\UuidCollection;

interface NegativeBudgetWarningsFetcherInterface
{
    public function fetchPotential(): WarningCollection;
    public function fetchOpen(): UuidCollection;
}
