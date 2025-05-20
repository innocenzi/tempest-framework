<?php

namespace Tempest\Debug\Server;

use Tempest\Router\Get;
use Tempest\Support\Filesystem;
use Tempest\View\View;

use function Tempest\view;

final class DebugController
{
    #[Get('/__debug')]
    public function __invoke(): View
    {
        if (! Filesystem\exists(__DIR__ . '/ui/dist/main.js')) {
            throw new \Exception('Debug UI not built. Run the `build` in the debug package.');
        }

        return view('./debug.view.php')->data(
            hydration: '',
            script: Filesystem\read_file(__DIR__ . '/ui/dist/main.js'),
            css: Filesystem\read_file(__DIR__ . '/ui/dist/main.css'),
        );
    }
}
