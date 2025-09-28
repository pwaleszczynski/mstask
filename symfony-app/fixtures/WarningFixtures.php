<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Core\Warning\Domain\Model\Warning;
use App\Core\Warning\Domain\WarningObjectType;
use App\Core\Warning\Domain\WarningType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class WarningFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $warning = new Warning(
            Uuid::v7(),
            Uuid::fromString(ContractorFixtures::CONTRACTOR_1_ID),
            WarningObjectType::CONTRACTOR,
            WarningType::ARREARS_EXCEEDED,
        );

        $manager->persist($warning);
        $manager->flush();
    }
}
