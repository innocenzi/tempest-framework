<?php

declare(strict_types=1);

namespace Tempest\Console;

use Closure;
use Tempest\Container\Container;
use Tempest\Core\CanBePublished;
use Tempest\Core\Composer;
use Tempest\Core\DoNotDiscover;
use Tempest\Generation\ClassManipulator;
use Tempest\Reflection\ClassReflector;
use function Tempest\Support\arr;
use Tempest\Support\PathHelper;
use Tempest\Validation\Rules\EndsWith;
use Tempest\Validation\Rules\NotEmpty;

final class MakeCommandFactory
{
    public function __construct(
        private readonly Container $container,
        private readonly Composer $composer,
        private readonly Console $console,
    ) {
    }

    public function create(MakeCommand $command): Closure
    {
        return function () use ($command) {
            /** @var MakeCommandDefinition[] $definitions */
            $definitions = $this->container->invoke($command->handler);

            foreach (arr($definitions) as $definition) {
                $suggestedPath = PathHelper::make(
                    $this->composer->mainNamespace->path,
                    basename((new ClassReflector($definition->stub))->getFilePath())
                );

                $targetPath = $this->promptTargetPath(
                    name: $definition->stub,
                    suggested: $suggestedPath,
                    rules: [new NotEmpty(), new EndsWith('.php')]
                );

                if (! $this->prepareFilesystem($targetPath)) {
                    return false;
                }

                $manipulator = new ClassManipulator($definition->stub);
                $manipulator->removeClassAttribute(CanBePublished::class);
                $manipulator->removeClassAttribute(DoNotDiscover::class);
                $manipulator->setNamespace(PathHelper::toNamespace($targetPath));

                file_put_contents($targetPath, $manipulator->print());
            }
        };
    }

    private function prepareFilesystem(string $targetPath): bool
    {
        if (file_exists($targetPath)) {
            $override = $this->console->confirm(
                question: sprintf('%s already exists, do you want to overwrite it?', $targetPath),
                default: false,
            );

            if (! $override) {
                return false;
            }

            @unlink($targetPath);
        }

        if (! file_exists(dirname($targetPath))) {
            mkdir(dirname($targetPath), recursive: true);
        }

        return true;
    }

    private function promptTargetPath(string $name, string $suggested, array $rules = []): string
    {
        return $this->console->ask(
            question: sprintf('Where do you want to publish %s?', $name),
            default: $suggested,
            validation: $rules
        );
    }
}
