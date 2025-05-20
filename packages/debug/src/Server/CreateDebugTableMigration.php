<?php

namespace Tempest\Debug\Server;

use Tempest\Database\DatabaseMigration;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tempest\Database\QueryStatements\DropTableStatement;
use Tempest\Discovery\SkipDiscovery;

#[SkipDiscovery]
final class CreateDebugTableMigration implements DatabaseMigration
{
    public string $name = '2025-05-20_create_debug_table';

    public function up(): ?QueryStatement
    {
        return new CreateTableStatement('items')
            ->primary()
            ->text('type')
            ->json('content');
    }

    public function down(): ?QueryStatement
    {
        return new DropTableStatement('items');
    }
}
