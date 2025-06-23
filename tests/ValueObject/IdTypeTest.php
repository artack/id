<?php

declare(strict_types=1);

namespace Artack\Tests\Id\ValueObject;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class IdTypeTest extends TestCase
{
    private const string UUID_RFC4122 = '01890a5d-ac91-7d5a-bc39-37311668557b';
    
    private ConcreteIdType $type;
    private AbstractPlatform $platform;
    
    protected function setUp(): void
    {
        $this->type = new ConcreteIdType();
        
        // Create a mock for AbstractPlatform
        $this->platform = $this->createMock(AbstractPlatform::class);
        $this->platform->method('getGuidTypeDeclarationSQL')
            ->willReturn('GUID_TYPE');
    }
    
    public function testGetSQLDeclaration(): void
    {
        $result = $this->type->getSQLDeclaration([], $this->platform);
        
        self::assertSame('GUID_TYPE', $result);
    }
    
    public function testConvertToDatabaseValueWithNull(): void
    {
        $result = $this->type->convertToDatabaseValue(null, $this->platform);
        
        self::assertNull($result);
    }
    
    public function testConvertToDatabaseValueWithString(): void
    {
        $result = $this->type->convertToDatabaseValue(self::UUID_RFC4122, $this->platform);
        
        self::assertSame(self::UUID_RFC4122, $result);
    }
    
    public function testConvertToDatabaseValueWithIdObject(): void
    {
        $id = ConcreteId::fromRfc4122(self::UUID_RFC4122);
        
        $result = $this->type->convertToDatabaseValue($id, $this->platform);
        
        self::assertSame(self::UUID_RFC4122, $result);
    }
    
    public function testConvertToPHPValueWithNull(): void
    {
        $result = $this->type->convertToPHPValue(null, $this->platform);
        
        self::assertNull($result);
    }
    
    public function testConvertToPHPValueWithString(): void
    {
        $result = $this->type->convertToPHPValue(self::UUID_RFC4122, $this->platform);
        
        self::assertInstanceOf(ConcreteId::class, $result);
        self::assertSame(self::UUID_RFC4122, $result->toRfc4122());
    }
    
    public function testGetClass(): void
    {
        self::assertSame(ConcreteId::class, ConcreteIdType::getClass());
    }
}