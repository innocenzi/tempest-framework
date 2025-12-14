<?php

namespace Tempest\Debug\Backtrace;

use Tempest\Container\GenericContainer;
use Tempest\Mapper\SerializeAs;
use Throwable;

use function Tempest\root_path;

#[SerializeAs('backtrace')]
final class Backtrace
{
    /**
     * @param Frame[] $frames
     */
    public function __construct(
        public array $frames = [],
    ) {}

    public function first(): Frame
    {
        if (empty($this->frames)) {
            throw new \RuntimeException('Backtrace is empty.');
        }

        return $this->frames[0];
    }

    public static function createFromFrames(array $frames, ?string $file = null, ?int $line = null): self
    {
        $currentFile = $file ?: '';
        $currentLine = $line ?: 0;
        $frameObjects = [];

        foreach ($frames as $frame) {
            $frameObjects[] = new Frame(
                file: $currentFile,
                line: $currentLine,
                method: $frame['function'] ?? null,
                class: $frame['class'] ?? null,
                arguments: self::mapArgumentsToNames($frame['args'] ?? [], $frame['object'] ?? null, $frame['function'] ?? ''),
                instance: $frame['object'] ?? null,
                vendor: self::isApplicationFrame($frame['file'] ?? null),
            );

            $currentFile = $frame['file'] ?? 'unknown';
            $currentLine = $frame['line'] ?? 0;
        }

        foreach ($frameObjects as $i => $frame) {
            if ($frame->class !== static::class) {
                break;
            }

            unset($frameObjects[$i]);
        }

        return new self(array_values($frameObjects));
    }

    /**
     * Creates a backtrace from a {@see \Throwable}.
     */
    public static function fromThrowable(\Throwable $throwable): self
    {
        return self::createFromFrames(
            frames: $throwable->getTrace(),
            file: $throwable->getFile(),
            line: $throwable->getLine(),
        );
    }

    /**
     * Creates a backtrace.
     *
     * @param bool $arguments If `true`, the frames will include arguments.
     * @param bool $instance If `true`, the frames will include the instance of their class.
     */
    public static function create(int $limit = 0, bool $arguments = false, bool $instance = false): self
    {
        $options = DEBUG_BACKTRACE_IGNORE_ARGS;

        if ($arguments) {
            $options = 0;
        }

        if ($instance) {
            $options = $options | DEBUG_BACKTRACE_PROVIDE_OBJECT;
        }

        if ($limit !== 0) {
            $limit += 3;
        }

        return self::createFromFrames(debug_backtrace($options, $limit));
    }

    private static function mapArgumentsToNames(array $arguments, mixed $instance, string $method): array
    {
        if (! $instance) {
            return $arguments;
        }

        $reflection = new \ReflectionObject($instance);
        $methodReflection = $reflection->getMethod($method);
        $parameters = $methodReflection->getParameters();
        $mapped = [];

        foreach ($parameters as $index => $parameter) {
            $mapped[$parameter->getName()] = $arguments[$index] ?? null;
        }

        return $mapped;
    }

    private static function isApplicationFrame(?string $file): bool
    {
        if (! $file) {
            return true;
        }

        if (! class_exists(GenericContainer::class)) {
            return true;
        }

        if (is_null(GenericContainer::instance())) {
            return true;
        }

        $relative = str_replace(root_path(), '', $file);

        if (str_starts_with($relative, 'vendor/')) {
            return false;
        }

        return true;
    }
}
