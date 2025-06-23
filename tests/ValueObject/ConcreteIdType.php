<?php

declare(strict_types=1);

namespace Artack\Tests\Id\ValueObject;

use Artack\Id\ValueObject\IdType;
use Override;

/**
 * Concrete implementation of the abstract IdType class for testing purposes.
 */
final class ConcreteIdType extends IdType
{
    public const string NAME = 'concrete_id_type';

    #[Override]
    public static function getClass(): string
    {
        return ConcreteId::class;
    }

}
