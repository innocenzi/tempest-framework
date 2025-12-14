<?php

namespace Tempest\Debug\Server;

use Tempest\Container\Container;
use Tempest\Core\AppConfig;
use Tempest\Core\DiscoveryConfig;
use Tempest\Core\KernelEvent;
use Tempest\Database\Builder\QueryBuilders\SelectQueryBuilder;
use Tempest\Database\Config\SQLiteConfig;
use Tempest\Database\Direction;
use Tempest\Database\Migrations\CreateMigrationsTable;
use Tempest\Database\Migrations\MigrationManager;
use Tempest\Database\Migrations\RunnableMigrations;
use Tempest\EventBus\EventHandler;
use Tempest\Support\Path;

use function Tempest\Database\query;
use function Tempest\env;
use function Tempest\internal_storage_path;

final class DebugServer
{
    public const TAG = 'tempest-debug-server';

    public function __construct(
        private AppConfig $config,
        private DiscoveryConfig $discoveryConfig,
        private Container $container,
    ) {}

    #[EventHandler(KernelEvent::BOOTED)]
    public function setup(): void
    {
        $this->discoveryConfig->skipPaths(Path\normalize(__DIR__ . '/ui'));

        if (! $this->isEnabled()) {
            return;
        }

        $this->configureDatabase();

        // TODO: register routes manually
    }

    public function isEnabled(): bool
    {
        return env('DEBUG', default: $this->config->environment->isLocal());
    }

    public function clear(): void
    {
        query(DebugItem::class)
            ->delete()
            ->allowAll()
            ->onDatabase(self::TAG)
            ->execute();
    }

    /** @return DebugItem[] */
    public function getLastItems(?int $lastSeenId, bool $ascending): array
    {
        return query(DebugItem::class)
            ->select()
            ->orderBy('id', direction: $ascending ? Direction::ASC : Direction::DESC)
            ->when($lastSeenId, fn (SelectQueryBuilder $builder) => $builder->where('id > :id', id: $lastSeenId))
            ->when(! $lastSeenId, fn (SelectQueryBuilder $builder) => $builder->limit(10))
            ->onDatabase(self::TAG)
            ->all();
    }

    private function configureDatabase(): void
    {
        $this->container->config(new SQLiteConfig(
            path: internal_storage_path('/debug.sqlite'),
            tag: self::TAG,
        ));

        $manager = new MigrationManager(
            migrations: new RunnableMigrations([new CreateMigrationsTable(), new CreateDebugItemsTable()]),
            container: $this->container,
        );

        $manager->onDatabase(self::TAG)->up();
    }
}
