<?php

declare(strict_types=1);

namespace Tests\Tempest\Integration\Database;

use Tests\Tempest\Fixtures\Models\ModelWithTableAttribute;
use Tests\Tempest\Integration\FrameworkIntegrationTestCase;

/**
 * @internal
 */
final class DatabaseModelTest extends FrameworkIntegrationTestCase
{
    public function test_table_attribute(): void
    {
        $this->assertSame('foo_bar_baz', ModelWithTableAttribute::table()->tableName);
    }
}
