<?php

declare(strict_types=1);

namespace Artack\Tests\Id\Doctrine\Platform;

use Artack\Id\Doctrine\Platform\MariaDB1011Platform;
use Doctrine\DBAL\Types\Types;
use PHPUnit\Framework\TestCase;

final class MariaDB1011PlatformTest extends TestCase
{
    private MariaDB1011Platform $platform;

    protected function setUp(): void
    {
        $this->platform = new MariaDB1011Platform();
    }

    public function testGetGuidTypeDeclarationSQL(): void
    {
        $result = $this->platform->getGuidTypeDeclarationSQL([]);

        self::assertSame('UUID', $result);
    }

    public function testDoctrineTypeMappings(): void
    {
        // Test that the platform correctly maps 'uuid' to Types::GUID
        // by checking the return type of a column with type 'uuid'
        $type = $this->platform->getDoctrineTypeMapping('uuid');

        self::assertSame(Types::GUID, $type);
    }
}
