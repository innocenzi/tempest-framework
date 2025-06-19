<?php

namespace Tempest\Debug\Backtrace;

final class Frame
{
    public function __construct(
        public string $file,
        public int $line,
        public ?string $method,
        public ?string $class,
        public array $arguments,
        public mixed $instance,
        public bool $vendor,
    ) {}

    /**
     * Returns the path of the file and line number where this frame was called.
     */
    public function getCallPath(): string
    {
        return $this->file . ':' . $this->line;
    }
}
