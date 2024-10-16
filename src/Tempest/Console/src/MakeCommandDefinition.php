<?php

declare(strict_types=1);

namespace Tempest\Console;

final class MakeCommandDefinition
{
    public function __construct(
        public readonly string $stub,
        public readonly ?string $pattern = null,
    ) {
    }
}
