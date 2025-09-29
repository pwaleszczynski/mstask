<?php

declare(strict_types=1);

namespace App\Finance\Domain\Model;

use App\Finance\Domain\ValueObject\InvoiceAmount;
use App\Shared\Domain\ValueObject\NotEmptyString;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[Gedmo\SoftDeleteable]
#[ORM\Table(name: 'invoices')]
class Invoice
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $amount;
    #[ORM\Column(type: Types::STRING, length:100, nullable: false, unique:true)]
    private string $number;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: UuidType::NAME, unique: true)]
        private readonly Uuid $id,
        #[ORM\ManyToOne(targetEntity: Contractor::class)]
        #[ORM\JoinColumn(name: 'contractor_id', referencedColumnName: 'id')]
        private readonly Contractor $contractor,
        #[ORM\Column(type: Types::BOOLEAN)]
        private readonly bool $paid,
        #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: false)]
        private readonly \DateTimeImmutable $paymentDate,
        NotEmptyString $number,
        InvoiceAmount $amount,
    ){
        $this->number = $number->toString();
        $this->amount = $amount->getAmount();
    }
}
