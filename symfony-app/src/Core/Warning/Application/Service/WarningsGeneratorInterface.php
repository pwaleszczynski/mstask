<?php

declare(strict_types=1);

namespace App\Core\Warning\Application\Service;

use App\Core\Warning\Application\Model\GeneratorOutput;

interface WarningsGeneratorInterface
{
    public function generate(): GeneratorOutput;
}
