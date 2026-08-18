<?php

declare(strict_types=1);

namespace Artack\Id\Serializer;

use Artack\Id\ValueObject\Id;

abstract readonly class IdSupportedTypes
{
    /**
     * @return array<string, bool|null>
     */
    final public function getSupportedTypes(?string $format): array
    {
        return [
            Id::class => true,
        ];
    }
}
