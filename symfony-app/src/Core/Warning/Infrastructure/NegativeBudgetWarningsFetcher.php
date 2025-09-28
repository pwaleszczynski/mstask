<?php

declare(strict_types=1);

namespace App\Core\Warning\Infrastructure;

use App\Core\Warning\Application\Model\Warning;
use App\Core\Warning\Application\Service\NegativeBudgetWarningsFetcherInterface;
use App\Core\Warning\Domain\WarningObjectType;
use App\Core\Warning\Domain\WarningType;
use App\Core\Warning\Application\Model\WarningCollection;
use App\Shared\Domain\Collection\UuidCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

final class NegativeBudgetWarningsFetcher implements NegativeBudgetWarningsFetcherInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function fetchPotential(): WarningCollection
    {
        $potentialWarnings = [];

        $results = $this->entityManager->getConnection()->executeQuery(
            <<<SQL
                SELECT
                    b.id, w.id AS warningId
                FROM budgets b
                LEFT JOIN warnings w ON w.object_id = b.id AND w.type = :negative_budget_type AND w.object_type = :budget_type AND w.deleted_at IS NULL
                WHERE b.amount < 0 AND b.deleted_at IS NULL
                ;
            SQL,
            [
                'negative_budget_type' => WarningType::NEGATIVE_BUDGET->value,
                'budget_type' => WarningObjectType::BUDGET->value,
            ],
        )->fetchAllAssociative();

        foreach ($results as $result) {
            $potentialWarnings[] = new Warning(
                Uuid::fromString($result['id']),
                WarningObjectType::BUDGET,
                WarningType::NEGATIVE_BUDGET,
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
                WHERE w.type = :negative_budget_type AND w.object_type = :budget_type AND w.deleted_at IS NULL
                ;
            SQL,
            [
                'negative_budget_type' => WarningType::NEGATIVE_BUDGET->value,
                'budget_type' => WarningObjectType::BUDGET->value,
            ],
        )->fetchAllAssociative();

        foreach ($results as $result) {
            $uuids[] = Uuid::fromString($result['id']);
        }

        return new UuidCollection($uuids);
    }
}
