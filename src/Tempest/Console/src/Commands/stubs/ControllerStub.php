<?php

declare(strict_types=1);

namespace Tempest\Console\Commands\stubs;

use Tempest\Http\Get;
use function Tempest\view;
use Tempest\View\View;

final readonly class ControllerStub
{
    #[Get('/welcome')]
    public function __invoke(): View
    {
        return view('welcome');
    }
}
