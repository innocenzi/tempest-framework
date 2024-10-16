<?php

declare(strict_types=1);

namespace Tempest\Console\Actions;

use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;
use Tempest\Console\Input\ConsoleArgumentDefinition;
use function Tempest\Support\str;

final readonly class RenderConsoleCommand
{
    public function __construct(private Console $console)
    {
    }

    public function __invoke(ConsoleCommand $consoleCommand): void
    {
        $parts = ["<em><strong>{$consoleCommand->getName()}</strong></em>"];

        foreach ($consoleCommand->getArgumentDefinitions() as $arguments) {
            $parts[] = $this->renderArgument($arguments);
        }

        if ($consoleCommand->description !== null && $consoleCommand->description !== '') {
            $parts[] = "- {$consoleCommand->description}";
        }

        $this->console->writeln(' ' . implode(' ', $parts));
    }

    private function renderArgument(ConsoleArgumentDefinition $parameter): string
    {
        $name = str($parameter->name)
            ->kebab()
            ->prepend('<em>')
            ->append('</em>');

        $asString = match($parameter->type) {
            'bool' => "<em>--</em>{$name}",
            default => $name,
        };

        if (! $parameter->hasDefault) {
            return "<{$asString}>";
        }

        return match (true) {
            $parameter->default === true => "[{$asString}=true]",
            $parameter->default === false => "[{$asString}=false]",
            is_null($parameter->default) => "[{$asString}=null]",
            is_array($parameter->default) => "[{$asString}=array]",
            default => "[{$asString}={$parameter->default}]"
        };
    }
}
