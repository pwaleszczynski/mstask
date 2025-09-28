<?php

declare(strict_types=1);

namespace App\Core\Warning\Infrastructure;

use App\Core\Warning\Domain\Model\Warning;
use App\Core\Warning\Domain\Model\WarningCollection;
use App\Core\Warning\Domain\Repository\WarningRepositoryInterface;
use App\Shared\Domain\Collection\UuidCollection;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

final class WarningRepository implements WarningRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function addBatch(WarningCollection $warnings): void
    {
        $batchSize = 20;
        $i = 0;

        foreach ($warnings->getItems() as $warning) {
            $this->entityManager->persist($warning);
            ++$i;

            if (($i % $batchSize) === 0) {
                $this->entityManager->flush();
                $this->entityManager->clear();
            }
        }

        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    public function delete(UuidCollection $warnings): void
    {
        $ids = \array_map(
            static fn (Uuid $uuid): string => $uuid->toBinary(),
            $warnings->getItems(),
        );

        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->select('w')
            ->from(Warning::class, 'w')
            ->where('w.id IN (:ids)')
            ->setParameter('ids', $ids);

        $entities = $qb->getQuery()->getResult(AbstractQuery::HYDRATE_OBJECT);
        $batchSize = 20;
        $i = 0;

        foreach($entities as $entity) {
            $this->entityManager->remove($entity);
            ++$i;
            if (($i % $batchSize) === 0) {
                $this->entityManager->flush();
                $this->entityManager->clear();
            }
        }
        $this->entityManager->flush();
    }
}
