<?php

declare(strict_types=1);

namespace Artack\Id\Serializer;

use Artack\Id\ValueObject\Id;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final readonly class IdDenormalizer extends IdSupportedTypes implements DenormalizerInterface
{
    /**
     * @param null|string      $data
     * @param class-string<Id> $type
     */
    #[Override]
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): ?Id
    {
        if (null === $data) {
            return null;
        }

        return $type::fromRfc4122($data);
    }

    /**
     * @param class-string $type
     */
    #[Override]
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return class_exists($type) && is_subclass_of($type, Id::class, true);
    }
}
