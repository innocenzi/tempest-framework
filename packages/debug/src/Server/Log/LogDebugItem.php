<?php

namespace Tempest\Debug\Server\Log;

use Tempest\Mapper\SerializeAs;

#[SerializeAs('log_debug_item')]
final class LogDebugItem
{
    public function __construct(
        private(set) string $content,
    ) {}
}
