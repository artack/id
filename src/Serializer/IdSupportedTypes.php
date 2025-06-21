<?php

declare(strict_types=1);

namespace Artack\Id\Serializer;

use Artack\Id\ValueObject\Id;

abstract readonly class IdSupportedTypes
{
    /**
     * @return array<string, null|bool>
     */
    final public function getSupportedTypes(?string $format): array
    {
        return [
            Id::class => true,
        ];
    }
}
