<?php

declare(strict_types=1);

namespace App\Core\Warning\Domain\Model;

use App\Core\Warning\Domain\WarningObjectType;
use App\Core\Warning\Domain\WarningType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[Gedmo\SoftDeleteable]
#[ORM\Table(name: 'warnings')]
final class Warning
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[ORM\Column(type: Types::STRING, length:100, nullable: false)]
    private string $objectType;
    #[ORM\Column(type: Types::STRING, length:100, nullable: false)]
    private string $type;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: UuidType::NAME, unique: true)]
        private readonly Uuid $id,
        #[ORM\Column(type: UuidType::NAME, nullable: false)]
        private readonly Uuid $objectId,
        WarningObjectType $objectType,
        WarningType $type,
    ) {
        $this->objectType = $objectType->value;
        $this->type = $type->value;
    }
}
