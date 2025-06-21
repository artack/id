<?php

declare(strict_types=1);

namespace Artack\Id\Serializer;

use Artack\Id\ValueObject\Id;
use Override;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final readonly class IdNormalizer extends IdSupportedTypes implements NormalizerInterface
{
    /**
     * @param ?Id $data
     */
    #[Override]
    public function normalize(mixed $data, ?string $format = null, array $context = []): ?string
    {
        return $data?->toRfc4122();
    }

    #[Override]
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Id;
    }
}
