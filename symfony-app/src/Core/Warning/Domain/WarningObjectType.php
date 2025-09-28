<?php

declare(strict_types=1);

namespace App\Core\Warning\Domain;

enum WarningObjectType: string
{
    case BUDGET = 'budget';
    case CONTRACTOR = 'contractor';
    case INVOICE = 'invoice';
}
