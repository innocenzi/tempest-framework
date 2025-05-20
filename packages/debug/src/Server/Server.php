<?php

namespace Tempest\Debug\Server;

use Generator;
use Tempest\Clock\Clock;
use Tempest\Container\Container;
use Tempest\Database\Config\SQLiteConfig;
use Tempest\Database\Database;
use Tempest\Database\Query;
use Tempest\Debug\ItemsDebugged;
use Tempest\EventBus\EventHandler;
use Tempest\Support\Json;

use function Tempest\internal_storage_path;

final class Server
{
    public const TAG = 'tempest-debug-server';

    private Database $database {
        get => $this->container->get(Database::class, tag: self::TAG);
    }

    public function __construct(
        private Container $container,
        private Clock $clock,
    ) {}

    private function setup(): void
    {
        $this->container->config(new SQLiteConfig(
            path: internal_storage_path('/debug.sqlite'),
            tag: self::TAG,
        ));

        $this->database->execute(
            new Query(<<<SQL
                CREATE TABLE IF NOT EXISTS items (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    type TEXT NOT NULL,
                    value TEXT NOT NULL
                )
            SQL),
        );
    }

    public function stream(): Generator
    {
        $this->setup();

        while (true) {
            $value = $this->unshift();

            if (! $value) {
                $this->clock->sleep(milliseconds: 1000);
                continue;
            }

            yield $value;

            $this->clock->sleep(milliseconds: 50);
        }
    }

    private function unshift(): mixed
    {
        $item = $this->database->fetchFirst(new Query('SELECT * FROM items'));

        if (! $item) {
            return null;
        }

        $this->database->execute(new Query('DELETE FROM items WHERE id = :id', ['id' => $item['id']]));

        return $item;
    }

    #[EventHandler(ItemsDebugged::class)]
    public function storeDebugItems(ItemsDebugged $items): void
    {
        $this->setup();

        foreach ($items->items as $item) {
            $this->database->execute(
                new Query('INSERT INTO items (type, value) VALUES (:type, :value)', [
                    'type' => 'debug',
                    'value' => Json\encode($item),
                ]),
            );
        }
    }
}
