<?php

namespace Tempest\Debug\Server\Exception;

use Tempest\Debug\Backtrace\Backtrace;
use Tempest\Mapper\SerializeAs;

#[SerializeAs('exception_debug_item')]
final class ExceptionDebugItem
{
    public function __construct(
        private(set) string $message,
        private(set) Backtrace $backtrace,
    ) {}
}
