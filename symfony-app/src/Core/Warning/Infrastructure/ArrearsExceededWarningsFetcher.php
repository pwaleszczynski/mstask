<?php

declare(strict_types=1);

namespace App\Core\Warning\Infrastructure;

use App\Core\Warning\Application\Model\Warning;
use App\Core\Warning\Application\Model\WarningCollection;
use App\Core\Warning\Application\Service\ArrearsExceededWarningsFetcherInterface;
use App\Core\Warning\Domain\ArrearsExceededWarning;
use App\Core\Warning\Domain\WarningObjectType;
use App\Core\Warning\Domain\WarningType;
use App\Shared\Domain\Collection\UuidCollection;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;
use Symfony\Component\Uid\Uuid;

final class ArrearsExceededWarningsFetcher implements ArrearsExceededWarningsFetcherInterface
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
                    i.contractor_id AS id, w.id AS warningId, SUM(i.amount), i.paid, i.payment_date, i.deleted_at
                FROM invoices i
                JOIN contractors c ON c.id = i.contractor_id AND c.deleted_at IS NULL 
                LEFT JOIN warnings w ON w.object_id = i.contractor_id AND w.type = :arrears_exceeded_type AND w.object_type = :contractor_type AND w.deleted_at IS NULL
                GROUP BY i.contractor_id
                HAVING SUM(i.amount) > :warning_amount AND i.paid = 0 AND i.payment_date < :now AND i.deleted_at IS NULL
                ;
            SQL,
            [
                'arrears_exceeded_type' => WarningType::ARREARS_EXCEEDED->value,
                'contractor_type' => WarningObjectType::CONTRACTOR->value,
                'now' => $this->clock->now()->format(\DateTimeInterface::ATOM),
                'warning_amount' => ArrearsExceededWarning::MIN_AMOUNT,
            ],
        )->fetchAllAssociative();

        foreach ($results as $result) {
            $potentialWarnings[] = new Warning(
                Uuid::fromString($result['id']),
                WarningObjectType::CONTRACTOR,
                WarningType::ARREARS_EXCEEDED,
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
                WHERE w.type = :arrears_exceeded_type AND w.object_type = :contractor_type AND w.deleted_at IS NULL
                ;
            SQL,
            [
                'arrears_exceeded_type' => WarningType::ARREARS_EXCEEDED->value,
                'contractor_type' => WarningObjectType::CONTRACTOR->value,
            ],
        )->fetchAllAssociative();

        foreach ($results as $result) {
            $uuids[] = Uuid::fromString($result['id']);
        }

        return new UuidCollection($uuids);
    }
}
