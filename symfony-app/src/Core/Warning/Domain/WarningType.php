<?php

declare(strict_types=1);

namespace App\Core\Warning\Domain;

enum WarningType: string
{
    case ARREARS_EXCEEDED = 'arrears-exceeded';
    case NEGATIVE_BUDGET = 'negative-budget';
    case OVERDUE_INVOICE = 'overdue-invoice';
}
