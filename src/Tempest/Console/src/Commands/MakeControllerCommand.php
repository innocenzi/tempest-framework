<?php

declare(strict_types=1);

namespace Tempest\Console\Commands;

use Tempest\Console\Commands\stubs\ControllerStub;
use Tempest\Console\Commands\stubs\ControllerTestStub;
use Tempest\Console\ConsoleArgument;
use Tempest\Console\MakeCommand;
use Tempest\Console\MakeCommandDefinition;

final class MakeControllerCommand
{
    #[MakeCommand(name: 'controller')]
    public function __invoke(
        #[ConsoleArgument('Whether to generate a test file')]
        bool $test = false,
    ): array {
        $definitions = [];

        if ($test) {
            $definitions[] = new MakeCommandDefinition(
                stub: ControllerTestStub::class,
            );
        }

        $definitions[] = new MakeCommandDefinition(
            stub: ControllerStub::class,
        );

        return $definitions;
    }
}
