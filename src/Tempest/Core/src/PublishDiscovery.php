<?php

declare(strict_types=1);

namespace Tempest\Core;

use Tempest\Container\Container;
use Tempest\Reflection\ClassReflector;

final readonly class PublishDiscovery implements Discovery
{
    public const string CACHE_PATH = __DIR__ . '/../../../../.cache/tempest/publish-discovery.cache.php';

    public function __construct(
        private Kernel $kernel,
    ) {
    }

    public function discover(ClassReflector $class): void
    {
        if ($class->getName() === self::class) {
            return;
        }

        if (! $class->hasAttribute(CanBePublished::class)) {
            return;
        }

        $this->kernel->publishClasses[] = $class->getName();
    }

    public function hasCache(): bool
    {
        return file_exists(self::CACHE_PATH);
    }

    public function storeCache(): void
    {
        $directory = pathinfo(self::CACHE_PATH, PATHINFO_DIRNAME);

        if (! is_dir($directory)) {
            mkdir($directory, recursive: true);
        }

        file_put_contents(self::CACHE_PATH, serialize($this->kernel->publishClasses));
    }

    public function restoreCache(Container $container): void
    {
        $classes = unserialize(file_get_contents(self::CACHE_PATH), [
            'allowed_classes' => true,
        ]);

        $this->kernel->publishClasses = $classes;
    }

    public function destroyCache(): void
    {
        @unlink(self::CACHE_PATH);
    }
}
