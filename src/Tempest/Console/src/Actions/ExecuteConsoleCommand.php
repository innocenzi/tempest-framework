<?php

declare(strict_types=1);

namespace Tempest\Console\Actions;

use Closure;
use Tempest\Console\ConsoleConfig;
use Tempest\Console\ConsoleInputBuilder;
use Tempest\Console\ExitCode;
use Tempest\Console\Initializers\Invocation;
use Tempest\Console\Input\ConsoleArgumentBag;
use Tempest\Console\MakeCommand;
use Tempest\Console\MakeCommandFactory;
use Tempest\Container\Container;

final readonly class ExecuteConsoleCommand
{
    public function __construct(
        private Container $container,
        private ConsoleConfig $consoleConfig,
        private ConsoleArgumentBag $argumentBag,
    ) {
    }

    public function __invoke(string $commandName): ExitCode
    {
        $callable = $this->getCallable($this->resolveCommandMiddleware($commandName));

        $this->argumentBag->setCommandName($commandName);

        return $callable(new Invocation(
            argumentBag: $this->argumentBag,
        ));
    }

    private function getCallable(array $commandMiddleware): Closure
    {
        $callable = function (Invocation $invocation) {
            $consoleCommand = $invocation->consoleCommand;

            if ($consoleCommand instanceof MakeCommand) {
                $class = $this->container->get(MakeCommandFactory::class);
                $handler = $class->create($consoleCommand);
            } else {
                $class = $this->container->get($consoleCommand->handler->getDeclaringClass()->getName());
                $handler = $consoleCommand->handler;
            }

            $inputBuilder = new ConsoleInputBuilder($consoleCommand, $invocation->argumentBag);

            $consoleCommand->handler->invokeArgs(
                $class,
                $inputBuilder->build(),
            );

            return ExitCode::SUCCESS;
        };

        $middlewareStack = [...$this->consoleConfig->middleware, ...$commandMiddleware];

        while ($middlewareClass = array_pop($middlewareStack)) {
            $callable = fn (Invocation $invocation) => $this->container->get($middlewareClass)($invocation, $callable);
        }

        return $callable;
    }

    private function resolveCommandMiddleware(string $commandName): array
    {
        $consoleCommand = $this->consoleConfig->commands[$commandName] ?? null;

        return $consoleCommand->middleware ?? [];
    }
}
