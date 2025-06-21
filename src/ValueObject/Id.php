<?php

declare(strict_types=1);

namespace Artack\Id\ValueObject;

use Symfony\Component\Uid\Uuid;

abstract readonly class Id
{
    final public function __construct(public Uuid $uuid) {}

    final public function __toString(): string
    {
        return $this->uuid->toRfc4122();
    }

    final public function toString(): string
    {
        return (string) $this->uuid;
    }

    final public static function generateRandom(): static
    {
        return new static(Uuid::v7());
    }

    final public static function fromBinary(string $value): static
    {
        return new static(Uuid::fromBinary($value));
    }

    final public static function fromRfc4122(string $value): static
    {
        return new static(Uuid::fromRfc4122($value));
    }

    final public static function fromBase58(string $value): static
    {
        return new static(Uuid::fromBase58($value));
    }

    final public function toBinary(): string
    {
        return $this->uuid->toBinary();
    }

    final public function toRfc4122(): string
    {
        return $this->uuid->toRfc4122();
    }

    final public function toBase58(): string
    {
        return $this->uuid->toBase58();
    }
}
