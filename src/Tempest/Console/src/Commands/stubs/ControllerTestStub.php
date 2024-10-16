<?php

declare(strict_types=1);

namespace Tempest\Console\Commands\stubs;

use PHPUnit\Framework\Attributes\Test;
use Tests\Tempest\Integration\FrameworkIntegrationTestCase;

/**
 * @internal
 */
final class ControllerTestStub extends FrameworkIntegrationTestCase
{
    #[Test]
    public function test_controller(): void
    {

    }
}
