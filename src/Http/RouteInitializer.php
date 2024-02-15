<?php

declare(strict_types=1);

namespace Tempest\Http;

use Tempest\AppConfig;
use Tempest\Interface\Container;
use Tempest\Interface\Initializer;
use Tempest\Interface\Router;

final readonly class RouteInitializer implements Initializer
{
    public function __construct(
        private Container $container,
        private AppConfig $appConfig,
        private RouteConfig $routeConfig,
    ) {
    }

    public function initialize(string $className, Container $container): GenericRouter
    {
        $router = new GenericRouter(
            container: $this->container,
            appConfig: $this->appConfig,
            routeConfig: $this->routeConfig,
        );

        $this->container->singleton(Router::class, fn () => $router);

        return $router;
    }
}
