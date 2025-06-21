<?php

declare(strict_types=1);

namespace Artack\Id\Doctrine\Platform;

use Doctrine\DBAL\Platforms\MariaDB1010Platform;
use Doctrine\DBAL\Types\Types;

/**
 * copy from: https://github.com/doctrine/dbal/pull/5990.
 */
final class MariaDB1011Platform extends MariaDB1010Platform
{
    public function getGuidTypeDeclarationSQL(array $column): string
    {
        return 'UUID';
    }

    protected function initializeDoctrineTypeMappings(): void
    {
        parent::initializeDoctrineTypeMappings();

        $this->doctrineTypeMapping['uuid'] = Types::GUID;
    }
}
