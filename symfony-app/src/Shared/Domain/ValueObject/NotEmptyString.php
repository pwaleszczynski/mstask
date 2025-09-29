<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final class NotEmptyString
{
    const MAX_LENGTH = 100;

    private string $str;

    public function __construct(
        string $str,
    ) {
        if (empty($str) || \mb_strlen($str) > self::MAX_LENGTH) {
            throw new \DomainException('Invalid not empty string');
        }

        $this->str = $str;
    }

    public static function fromString(string $str): self
    {
        return new self($str);
    }

    public function toString(): string
    {
        return $this->str;
    }
}
