<?php

declare(strict_types=1);

namespace Tempest\Debug;

use Tempest\Debug\Backtrace\Backtrace;

final class ItemsDebugged
{
    public function __construct(
        public array $items,
        public Backtrace $backtrace,
    ) {}
}
