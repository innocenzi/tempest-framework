<?php

namespace Tempest\Debug\Server\Query;

use Tempest\Database\Config\DatabaseDialect;
use Tempest\Mapper\SerializeAs;

#[SerializeAs('query_debug_item')]
final class QueryDebugItem
{
    public function __construct(
        public string $sql,
        public string $databaseTag,
        public DatabaseDialect $databaseDialect,
    ) {}
}
