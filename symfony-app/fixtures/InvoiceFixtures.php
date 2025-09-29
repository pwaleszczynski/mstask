<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Finance\Domain\Model\Contractor;
use App\Finance\Domain\Model\Invoice;
use App\Finance\Domain\ValueObject\InvoiceAmount;
use App\Shared\Domain\ValueObject\NotEmptyString;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Psr\Clock\ClockInterface;
use Symfony\Component\Uid\Uuid;

class InvoiceFixtures extends Fixture
{
    public function __construct(
        private readonly ClockInterface $clock,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var Contractor $contractor1 */
        $contractor1 = $this->getReference(ContractorFixtures::CONTRACTOR_1_ID, Contractor::class);
        /** @var Contractor $contractor2 */
        $contractor2 = $this->getReference(ContractorFixtures::CONTRACTOR_2_ID, Contractor::class);
        /** @var Contractor $contractor3 */
        $contractor3 = $this->getReference(ContractorFixtures::CONTRACTOR_3_ID, Contractor::class);
        /** @var Contractor $contractor4 */
        $contractor4 = $this->getReference(ContractorFixtures::CONTRACTOR_4_ID, Contractor::class);
        $now = $this->clock->now();

        $invoice1 = new Invoice(
            Uuid::v7(),
            $contractor1,
            true,
            $now,
            NotEmptyString::fromString('1/1/1'),
            InvoiceAmount::from(100.50),
        );

        $invoice2 = new Invoice(
            Uuid::v7(),
            $contractor1,
            false,
            $now,
            NotEmptyString::fromString('1/1/2'),
            InvoiceAmount::from(13500),
        );

        $invoice3 = new Invoice(
            Uuid::v7(),
            $contractor2,
            false,
            $now->add(new \DateInterval('P10D')),
            NotEmptyString::fromString('1/1/223323'),
            InvoiceAmount::from(10500),
        );

        $invoice4 = new Invoice(
            Uuid::v7(),
            $contractor2,
            false,
            $now->sub(new \DateInterval('P5D')),
            NotEmptyString::fromString('1/1/20000000'),
            InvoiceAmount::from(10000),
        );

        $invoice5 = new Invoice(
            Uuid::v7(),
            $contractor3,
            false,
            $now->sub(new \DateInterval('P5D')),
            NotEmptyString::fromString('1/1/VVV'),
            InvoiceAmount::from(20000),
        );

        $invoice6 = new Invoice(
            Uuid::v7(),
            $contractor3,
            false,
            $now->sub(new \DateInterval('P25D')),
            NotEmptyString::fromString('1/1/VVV/00'),
            InvoiceAmount::from(20000),
        );

        $invoice7 = new Invoice(
            Uuid::v7(),
            $contractor4,
            false,
            $now->sub(new \DateInterval('P25D')),
            NotEmptyString::fromString('1/1/VVV/008'),
            InvoiceAmount::from(200),
        );

        $invoice8 = new Invoice(
            Uuid::v7(),
            $contractor4,
            false,
            $now->sub(new \DateInterval('P25D')),
            NotEmptyString::fromString('1/1/VVV/0084'),
            InvoiceAmount::from(14800),
        );

        $manager->persist($invoice1);
        $manager->persist($invoice2);
        $manager->persist($invoice3);
        $manager->persist($invoice4);
        $manager->persist($invoice5);
        $manager->persist($invoice6);
        $manager->persist($invoice7);
        $manager->persist($invoice8);
        $manager->flush();
    }
}
