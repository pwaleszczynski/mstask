<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Finance\Domain\Model\Contractor;
use App\Shared\Domain\ValueObject\NotEmptyString;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class ContractorFixtures extends Fixture
{
    public const CONTRACTOR_1_ID = '019987ee-61c2-794f-a032-3e0ee947cfa7';
    public const CONTRACTOR_2_ID = '01998812-7ddc-7647-89e3-d7af6d838140';
    public const CONTRACTOR_3_ID = '0199881e-f501-7f14-8e24-ca60ad9bea71';
    public const CONTRACTOR_4_ID = '01998820-449f-7d7a-bf78-06cb2cecab5d';
    public const CONTRACTOR_5_ID = '01998822-9a04-7b1e-bbd8-f2718f0f8ccf';

    public function load(ObjectManager $manager): void
    {
        $contractor1 = new Contractor(
            Uuid::fromString(self::CONTRACTOR_1_ID),
            NotEmptyString::fromString('Lebsack and Sons'),
        );
        $contractor2 = new Contractor(
            Uuid::fromString(self::CONTRACTOR_2_ID),
            NotEmptyString::fromString('Morar-Waters'),
        );
        $contractor3 = new Contractor(
            Uuid::fromString(self::CONTRACTOR_3_ID),
            NotEmptyString::fromString('Block Inc'),
        );
        $contractor4 = new Contractor(
            Uuid::fromString(self::CONTRACTOR_4_ID),
            NotEmptyString::fromString('Kuhic Ltd'),
        );
        $contractor5 = new Contractor(
            Uuid::fromString(self::CONTRACTOR_5_ID),
            NotEmptyString::fromString('Hermiston-Kunde'),
        );

        $manager->persist($contractor1);
        $manager->persist($contractor2);
        $manager->persist($contractor3);
        $manager->persist($contractor4);
        $manager->persist($contractor5);
        $manager->flush();

        $this->addReference(self::CONTRACTOR_1_ID, $contractor1);
        $this->addReference(self::CONTRACTOR_2_ID, $contractor2);
        $this->addReference(self::CONTRACTOR_3_ID, $contractor3);
        $this->addReference(self::CONTRACTOR_4_ID, $contractor4);
        $this->addReference(self::CONTRACTOR_5_ID, $contractor5);
    }
}