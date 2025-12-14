<?php

namespace Tempest\Database;

use PDOStatement;

final readonly class QueryExecuted
{
    public function __construct(
        private(set) Query $query,
        private(set) PDOStatement $statement,
        private(set) Database $database,
    ) {}
}
