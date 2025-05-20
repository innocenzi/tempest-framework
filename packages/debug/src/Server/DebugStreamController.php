<?php

namespace Tempest\Debug\Server;

use Tempest\Http\Responses\EventStream;
use Tempest\Http\ServerSentEvent;
use Tempest\Router\Get;

final class DebugStreamController
{
    public function __construct(
        private Server $server,
    ) {}

    #[Get('/__debug/stream')]
    public function __invoke()
    {
        set_time_limit(0);

        return new EventStream(function () {
            yield new ServerSentEvent(data: null, event: 'connected');
            yield from $this->server->stream();
        });
    }
}
