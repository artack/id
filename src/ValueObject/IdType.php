<?php

declare(strict_types=1);

namespace Artack\Id\ValueObject;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Override;
use Webmozart\Assert\Assert;

use function is_string;

abstract class IdType extends Type implements IdTypeInterface
{
    abstract public static function getClass(): string;

    #[Override]
    final public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL($column);
    }

    #[Override]
    final public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        // if one does not use the subclass of Id and use just the Rfc4122 value return that directly.
        if (is_string($value)) {
            return $value;
        }

        Assert::isInstanceOf($value, Id::class);

        return $value->uuid->toRfc4122();
    }

    #[Override]
    final public function convertToPHPValue($value, AbstractPlatform $platform): ?Id
    {
        if (null === $value) {
            return null;
        }

        Assert::string($value);

        /** @var class-string<Id> $class */
        $class = static::getClass();

        return $class::fromRfc4122($value);
    }
}
