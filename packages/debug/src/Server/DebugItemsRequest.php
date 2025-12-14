<?php

namespace Tempest\Debug\Server;

use Tempest\Http\IsRequest;
use Tempest\Http\Request;
use Tempest\Mapper\MapFrom;

final class DebugItemsRequest implements Request
{
    use IsRequest;

    #[MapFrom('last-seen-id')]
    public ?int $lastSeenId = null;

    public bool $ascending;
}
