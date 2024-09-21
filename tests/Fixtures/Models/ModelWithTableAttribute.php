<?php

declare(strict_types=1);

namespace Tests\Tempest\Fixtures\Models;

use Tempest\Database\Attributes\Table;
use Tempest\Database\DatabaseModel;
use Tempest\Database\IsDatabaseModel;

#[Table(name: 'foo_bar_baz')]
final class ModelWithTableAttribute implements DatabaseModel
{
    use IsDatabaseModel;
}
