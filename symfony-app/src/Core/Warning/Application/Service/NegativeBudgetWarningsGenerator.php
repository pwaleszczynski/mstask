<?php

declare(strict_types=1);

namespace App\Core\Warning\Application\Service;

use App\Core\Warning\Application\Model\GeneratorOutput;
use App\Core\Warning\Application\Model\Warning;
use App\Core\Warning\Domain\Model\WarningCollection;
use App\Core\Warning\Domain\Repository\WarningRepositoryInterface;
use App\Core\Warning\Domain\WarningObjectType;
use App\Core\Warning\Domain\WarningType;
use App\Core\Warning\Domain\Model\Warning as WarningEntity;
use Symfony\Component\Uid\Uuid;

final class NegativeBudgetWarningsGenerator implements WarningsGeneratorInterface
{
    public function __construct(
        private readonly NegativeBudgetWarningsFetcherInterface $budgetsWarningsFetcher,
        private readonly WarningRepositoryInterface $repository,
    ) {
    }

    public function generate(): GeneratorOutput
    {
        $sustainedWarnings = 0;
        $sustainedIds = [];
        $newWarnings = [];
        $closedWarnings = [];
        $potentialWarnings = $this->budgetsWarningsFetcher->fetchPotential();
        $openWarnings = $this->budgetsWarningsFetcher->fetchOpen();

        /** @var Warning $potentialWarning */
        foreach ($potentialWarnings as $potentialWarning) {
            if ($potentialWarning->openWarningId instanceof Uuid) {
                $sustainedWarnings = $sustainedWarnings + 1;
                $sustainedIds[] = $potentialWarning->openWarningId->toString();
                continue;
            }

            $newWarnings[] = new WarningEntity(
                Uuid::v7(),
                $potentialWarning->objectId,
                WarningObjectType::BUDGET,
                WarningType::NEGATIVE_BUDGET,
            );
        }

        /** @var Uuid $open */
        foreach ($openWarnings->getItems() as $open) {
            if (!\in_array($open->toString(), $sustainedIds, true)) {
                $closedWarnings[] = $open;
            }
        }

        if (\count($newWarnings) > 0) {
            $this->repository->addBatch(new WarningCollection($newWarnings));
        }

        return new GeneratorOutput(
            type: WarningType::NEGATIVE_BUDGET,
            new: \count($newWarnings),
            sustained: $sustainedWarnings,
            closed: \count($closedWarnings),
        );
    }
}
