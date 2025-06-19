<?php

namespace Tempest\Debug\Server;

use Tempest\Http\HttpRequestFailed;
use Tempest\Http\Request;
use Tempest\Http\Responses\Ok;
use Tempest\Http\Status;
use Tempest\Intl\Number;
use Tempest\Router\Get;
use Tempest\Support\Filesystem;
use Tempest\View\View;

use function Tempest\map;
use function Tempest\view;

// TODO: do not register routes by default
final class DebugController
{
    public function __construct(
        private DebugServer $server,
    ) {}

    #[Get('/__debug')]
    public function __invoke(): View
    {
        if (! $this->server->isEnabled()) {
            throw new HttpRequestFailed(status: Status::NOT_FOUND);
        }

        if (! Filesystem\exists(__DIR__ . '/ui/dist/main.js')) {
            throw new \Exception('Debug UI not built. Run the `build` in the debug package.');
        }

        return view('./debug.view.php')->data(
            hydration: '',
            script: Filesystem\read_file(__DIR__ . '/ui/dist/main.js'),
            css: Filesystem\read_file(__DIR__ . '/ui/dist/main.css'),
        );
    }

    #[Get('/__debug/latest')]
    public function latest(Request $request): Ok
    {
        if (! $this->server->isEnabled()) {
            throw new HttpRequestFailed(status: Status::NOT_FOUND);
        }

        $last = Number\parse($request->get('last-seen-id'));
        $items = $this->server->getLastItems($last);

        // https://github.com/tempestphp/tempest-framework/issues/1286
        $result = [];
        foreach ($items as $item) {
            $result[] = map($item)->toArray();
        }

        return new Ok($result);
    }
}
