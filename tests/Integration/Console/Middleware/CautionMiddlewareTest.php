<?php

declare(strict_types=1);

namespace Tests\Tempest\Integration\Console\Middleware;

use Tempest\Core\AppConfig;
use Tempest\Core\Environment;
use Tests\Tempest\Integration\FrameworkIntegrationTestCase;

/**
 * @internal
 * @small
 */
final class CautionMiddlewareTest extends FrameworkIntegrationTestCase
{
    public function test_in_local(): void
    {
        $this->console
            ->call('cautioncommand')
            ->assertContains('CAUTION confirmed');
    }

    public function test_in_production(): void
    {
        $appConfig = $this->container->get(AppConfig::class);
        $appConfig->environment = Environment::PRODUCTION;

        $this->console
            ->call('cautioncommand')
            ->submit('yes')
            ->assertContains('CAUTION confirmed');
    }
}
