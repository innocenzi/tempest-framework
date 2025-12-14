<?php

namespace Tempest\Debug\Server;

use Tempest\Http\Response;
use Tempest\Http\Responses\NotFound;
use Tempest\Http\Responses\Ok;
use Tempest\Router\Get;
use Tempest\Router\Post;
use Tempest\Support\Arr;
use Tempest\Support\Filesystem;
use Tempest\View\View;

use function Tempest\Mapper\map;
use function Tempest\view;

// TODO: do not register routes by default
final class DebugController
{
    public function __construct(
        private DebugServer $server,
    ) {}

    #[Get('/__debug')]
    public function __invoke(): View|Response
    {
        if (! $this->server->isEnabled()) {
            return new NotFound();
        }

        if (! Filesystem\exists(__DIR__ . '/ui/dist/main.js')) {
            throw new \Exception('Debug UI not built. Run the `build` in the debug package.');
        }

        return view('./debug.view.php')->data(
            hydration: '',
            script: Filesystem\read_file(__DIR__ . '/ui/dist/main.js'),
            css: Filesystem\read_file(__DIR__ . '/ui/dist/style.css'),
        );
    }

    #[Get('/__debug/latest')]
    public function latest(DebugItemsRequest $request): Response
    {
        if (! $this->server->isEnabled()) {
            return new NotFound();
        }

        $items = $this->server->getLastItems($request->lastSeenId, $request->ascending);
        $result = Arr\map_iterable($items, fn (DebugItem $item) => map($item)->toArray());

        return new Ok($result);
    }

    #[Post('/__debug/clear')]
    public function clear(): Response
    {
        if (! $this->server->isEnabled()) {
            return new NotFound();
        }

        $this->server->clear();

        return new Ok();
    }
}
