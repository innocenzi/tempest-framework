<?php

namespace Tempest\Debug\Server\Query;

use Tempest\Core\KernelEvent;
use Tempest\Database\QueryExecuted;
use Tempest\DateTime\DateTime;
use Tempest\Debug\Server\DebugItem;
use Tempest\Debug\Server\DebugItemType;
use Tempest\Debug\Server\DebugServer;
use Tempest\EventBus\EventBus;
use Tempest\EventBus\EventHandler;
use Tempest\Support\Str;

use function Tempest\Database\query;

final class CollectQueries
{
    public function __construct(
        private EventBus $eventBus,
        private DebugServer $server,
    ) {}

    #[EventHandler(KernelEvent::BOOTED)]
    public function setup(): void
    {
        if (! $this->server->isEnabled()) {
            return;
        }

        $this->eventBus->listen($this->storeQueries(...));
    }

    private function storeQueries(QueryExecuted $event): void
    {
        if ($event->database->tag === DebugServer::TAG) {
            return;
        }

        query(DebugItem::class)
            ->insert(new DebugItem(
                id: null,
                created_at: DateTime::now(),
                request_id: (string) TEMPEST_START,
                type: DebugItemType::QUERY,
                data: new QueryDebugItem(
                    sql: $event->query->toRawSql(),
                    databaseTag: Str\parse($event->database->tag),
                    databaseDialect: $event->database->dialect,
                ),
            ))
            ->onDatabase(DebugServer::TAG)
            ->execute();
    }
}
