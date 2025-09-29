<?php

declare(strict_types=1);

namespace App\Core\Warning\Infrastructure;

use App\Core\Warning\Application\Model\Warning;
use App\Core\Warning\Application\Model\WarningCollection;
use App\Core\Warning\Application\Service\OverdueInvoiceWarningsFetcherInterface;
use App\Core\Warning\Domain\WarningObjectType;
use App\Core\Warning\Domain\WarningType;
use App\Shared\Domain\Collection\UuidCollection;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;
use Symfony\Component\Uid\Uuid;

final class OverdueInvoiceWarningsFetcher implements OverdueInvoiceWarningsFetcherInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ClockInterface $clock,
    ) {
    }

    public function fetchPotential(): WarningCollection
    {
        $potentialWarnings = [];

        $results = $this->entityManager->getConnection()->executeQuery(
            <<<SQL
                SELECT
                    i.id, w.id AS warningId
                FROM invoices i
                LEFT JOIN warnings w ON w.object_id = i.id AND w.type = :overdue_invoice_type AND w.object_type = :invoice_type AND w.deleted_at IS NULL
                WHERE i.paid = 0 AND i.payment_date < :now AND i.deleted_at IS NULL
                ;
            SQL,
            [
                'overdue_invoice_type' => WarningType::OVERDUE_INVOICE->value,
                'invoice_type' => WarningObjectType::INVOICE->value,
                'now' => $this->clock->now()->format(\DateTimeInterface::ATOM),
            ],
        )->fetchAllAssociative();

        foreach ($results as $result) {
            $potentialWarnings[] = new Warning(
                Uuid::fromString($result['id']),
                WarningObjectType::INVOICE,
                WarningType::OVERDUE_INVOICE,
                $result['warningId'] ? Uuid::fromString($result['warningId']) : null,
            );
        }

        return new WarningCollection($potentialWarnings);
    }

    public function fetchOpen(): UuidCollection
    {
        $uuids = [];

        $results = $this->entityManager->getConnection()->executeQuery(
            <<<SQL
                SELECT
                    w.id
                FROM warnings w
                WHERE w.type = :overdue_invoice_type AND w.object_type = :invoice_type AND w.deleted_at IS NULL
                ;
            SQL,
            [
                'overdue_invoice_type' => WarningType::OVERDUE_INVOICE->value,
                'invoice_type' => WarningObjectType::INVOICE->value,
            ],
        )->fetchAllAssociative();

        foreach ($results as $result) {
            $uuids[] = Uuid::fromString($result['id']);
        }

        return new UuidCollection($uuids);
    }
}
