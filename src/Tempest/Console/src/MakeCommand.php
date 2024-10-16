<?php

declare(strict_types=1);

namespace Tempest\Console;

use Attribute;

#[Attribute]
final class MakeCommand extends ConsoleCommand
{
    public function __construct(
        public readonly string $name
    ) {
        parent::__construct(
            name: "make:{$name}",
            description: "Creates a new {$name} class",
            aliases: ["{$name}:create"],
        );
    }
}
