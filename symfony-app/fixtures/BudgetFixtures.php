<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Finance\Domain\Model\Budget;
use App\Shared\Domain\ValueObject\NotEmptyString;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class BudgetFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $budget1 = new Budget(
            Uuid::v7(),
            0,
            NotEmptyString::fromString('Buy now car'),
        );
        $budget2 = new Budget(
            Uuid::v7(),
            -120.50,
            NotEmptyString::fromString('Vacations'),
        );
        $budget3 = new Budget(
            Uuid::v7(),
            200.25,
            NotEmptyString::fromString('Buy some snacks'),
        );
        $budget4 = new Budget(
            Uuid::v7(),
            22,
            NotEmptyString::fromString('Pay my bills'),
        );
        $budget5 = new Budget(
            Uuid::v7(),
            -100.65,
            NotEmptyString::fromString('Buy some books'),
        );

        $manager->persist($budget1);
        $manager->persist($budget2);
        $manager->persist($budget3);
        $manager->persist($budget4);
        $manager->persist($budget5);
        $manager->flush();
    }
}