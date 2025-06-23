<?php

declare(strict_types=1);

namespace Artack\Tests\Id\ValueObject;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class IdTest extends TestCase
{
    private const string UUID_RFC4122 = '01890a5d-ac91-7d5a-bc39-37311668557b';
    private const string UUID_BINARY = "\x01\x89\x0a\x5d\xac\x91\x7d\x5a\xbc\x39\x37\x31\x16\x68\x55\x7b";
    private const string UUID_BASE58 = '1BzmjTFK3wLawNWv7TNC7k';

    public function testConstructor(): void
    {
        $uuid = Uuid::fromRfc4122(self::UUID_RFC4122);
        $id = new ConcreteId($uuid);

        self::assertSame($uuid, $id->uuid);
    }

    public function testToString(): void
    {
        $uuid = Uuid::fromRfc4122(self::UUID_RFC4122);
        $id = new ConcreteId($uuid);

        self::assertSame(self::UUID_RFC4122, $id->toString());
    }

    public function testMagicToString(): void
    {
        $uuid = Uuid::fromRfc4122(self::UUID_RFC4122);
        $id = new ConcreteId($uuid);

        self::assertSame(self::UUID_RFC4122, (string) $id);
    }

    public function testGenerateRandom(): void
    {
        $id = ConcreteId::generateRandom();

        self::assertInstanceOf(ConcreteId::class, $id);
        self::assertInstanceOf(Uuid::class, $id->uuid);
    }

    public function testFromBinary(): void
    {
        $id = ConcreteId::fromBinary(self::UUID_BINARY);

        self::assertInstanceOf(ConcreteId::class, $id);
        self::assertSame(self::UUID_RFC4122, $id->toRfc4122());
    }

    public function testFromRfc4122(): void
    {
        $id = ConcreteId::fromRfc4122(self::UUID_RFC4122);

        self::assertInstanceOf(ConcreteId::class, $id);
        self::assertSame(self::UUID_RFC4122, $id->toRfc4122());
    }

    public function testFromBase58(): void
    {
        $id = ConcreteId::fromBase58(self::UUID_BASE58);

        self::assertInstanceOf(ConcreteId::class, $id);
        self::assertSame(self::UUID_RFC4122, $id->toRfc4122());
    }

    public function testToBinary(): void
    {
        $id = ConcreteId::fromRfc4122(self::UUID_RFC4122);

        self::assertSame(self::UUID_BINARY, $id->toBinary());
    }

    public function testToRfc4122(): void
    {
        $id = ConcreteId::fromRfc4122(self::UUID_RFC4122);

        self::assertSame(self::UUID_RFC4122, $id->toRfc4122());
    }

    public function testToBase58(): void
    {
        $id = ConcreteId::fromRfc4122(self::UUID_RFC4122);

        self::assertSame(self::UUID_BASE58, $id->toBase58());
    }
}
