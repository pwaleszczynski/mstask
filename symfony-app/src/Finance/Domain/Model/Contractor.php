<?php

declare(strict_types=1);

namespace App\Finance\Domain\Model;

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
#[ORM\Table(name: 'contractors')]
class Contractor
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[ORM\Column(type: Types::STRING, length:100, nullable: false)]
    private string $name;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: UuidType::NAME, unique: true)]
        private readonly Uuid $id,
        NotEmptyString $name,
    ) {
        $this->name = $name->toString();
    }
}
