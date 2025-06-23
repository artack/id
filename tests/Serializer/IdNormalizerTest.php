<?php

declare(strict_types=1);

namespace Artack\Tests\Id\Serializer;

use Artack\Id\Serializer\IdNormalizer;
use Artack\Id\ValueObject\Id;
use Artack\Tests\Id\ValueObject\ConcreteId;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Uid\Uuid;

final class IdNormalizerTest extends TestCase
{
    private const string UUID_RFC4122 = '01890a5d-ac91-7d5a-bc39-37311668557b';

    private IdNormalizer $normalizer;

    protected function setUp(): void
    {
        $this->normalizer = new IdNormalizer();
    }

    public function testNormalizeWithNull(): void
    {
        $result = $this->normalizer->normalize(null);

        self::assertNull($result);
    }

    public function testNormalizeWithId(): void
    {
        $id = ConcreteId::fromRfc4122(self::UUID_RFC4122);

        $result = $this->normalizer->normalize($id);

        self::assertSame(self::UUID_RFC4122, $result);
    }

    public function testSupportsNormalizationWithId(): void
    {
        $id = ConcreteId::fromRfc4122(self::UUID_RFC4122);

        $result = $this->normalizer->supportsNormalization($id);

        self::assertTrue($result);
    }

    public function testSupportsNormalizationWithNonId(): void
    {
        $object = new stdClass();

        $result = $this->normalizer->supportsNormalization($object);

        self::assertFalse($result);
    }

    public function testGetSupportedTypes(): void
    {
        $result = $this->normalizer->getSupportedTypes(null);

        self::assertIsArray($result);
        self::assertArrayHasKey(Id::class, $result);
    }
}
