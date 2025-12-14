<?php

namespace Tempest\Debug\Server\Log;

use Tempest\Core\KernelEvent;
use Tempest\DateTime\DateTime;
use Tempest\Debug\ItemsDebugged;
use Tempest\Debug\Server\DebugItem;
use Tempest\Debug\Server\DebugItemType;
use Tempest\Debug\Server\DebugServer;
use Tempest\EventBus\EventBus;
use Tempest\EventBus\EventHandler;
use Tempest\Support\Json;
use Throwable;

use function Tempest\Database\query;

final class CollectLogs
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

        $this->eventBus->listen($this->storeLogs(...));
    }

    private function storeLogs(ItemsDebugged $event): void
    {
        foreach ($event->items as $item) {
            if ($item instanceof Throwable) {
                continue;
            }

            query(DebugItem::class)
                ->insert(new DebugItem(
                    id: null,
                    created_at: DateTime::now(),
                    request_id: (string) TEMPEST_START,
                    type: DebugItemType::LOG,
                    data: new LogDebugItem(Json\encode($item)),
                ))
                ->onDatabase(DebugServer::TAG)
                ->execute();
        }
    }
}
