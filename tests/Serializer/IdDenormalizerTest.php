<?php

declare(strict_types=1);

namespace Artack\Tests\Id\Serializer;

use Artack\Id\Serializer\IdDenormalizer;
use Artack\Tests\Id\ValueObject\ConcreteId;
use PHPUnit\Framework\TestCase;
use stdClass;
use Artack\Id\ValueObject\Id;

final class IdDenormalizerTest extends TestCase
{
    private const string UUID_RFC4122 = '01890a5d-ac91-7d5a-bc39-37311668557b';

    private IdDenormalizer $denormalizer;

    protected function setUp(): void
    {
        $this->denormalizer = new IdDenormalizer();
    }

    public function testDenormalizeWithNull(): void
    {
        $result = $this->denormalizer->denormalize(null, ConcreteId::class);

        self::assertNull($result);
    }

    public function testDenormalizeWithString(): void
    {
        $result = $this->denormalizer->denormalize(self::UUID_RFC4122, ConcreteId::class);

        self::assertInstanceOf(ConcreteId::class, $result);
        self::assertSame(self::UUID_RFC4122, $result->toRfc4122());
    }

    public function testSupportsDenormalizationWithIdClass(): void
    {
        $result = $this->denormalizer->supportsDenormalization(self::UUID_RFC4122, ConcreteId::class);

        self::assertTrue($result);
    }

    public function testSupportsDenormalizationWithNonIdClass(): void
    {
        $result = $this->denormalizer->supportsDenormalization(self::UUID_RFC4122, stdClass::class);

        self::assertFalse($result);
    }

    public function testGetSupportedTypes(): void
    {
        $result = $this->denormalizer->getSupportedTypes(null);

        self::assertIsArray($result);
        self::assertArrayHasKey(Id::class, $result);
    }
}
