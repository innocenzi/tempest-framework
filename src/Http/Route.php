<?php

declare(strict_types=1);

namespace Tempest\Http;

use Attribute;

#[Attribute]
readonly class Route
{
    public function __construct(
        public string $uri,
        public Method $method,
    ) {
    }
}
