<?php

namespace Tempest\Debug\Server;

use Tempest\Database\DatabaseMigration;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tempest\Database\QueryStatements\DropTableStatement;

final class CreateDebugItemsTable implements DatabaseMigration
{
    public string $name = '2025-05-24_create_debug_items_table';

    public function up(): ?QueryStatement
    {
        return new CreateTableStatement('debug_items')
            ->primary()
            ->datetime('created_at')
            ->text('request_id', nullable: true)
            ->text('type')
            ->json('data')
            ->json('backtrace');
    }

    public function down(): ?QueryStatement
    {
        return new DropTableStatement('debug_items');
    }
}
