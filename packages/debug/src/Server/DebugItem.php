<?php

namespace Tempest\Debug\Server;

use Tempest\DateTime\DateTime;
use Tempest\DateTime\FormatPattern;
use Tempest\Debug\Server\Exception\ExceptionDebugItem;
use Tempest\Debug\Server\Log\LogDebugItem;
use Tempest\Debug\Server\Query\QueryDebugItem;
use Tempest\Validation\Rules\HasDateTimeFormat;

final readonly class DebugItem
{
    public function __construct(
        public ?int $id,
        #[HasDateTimeFormat(FormatPattern::ISO8601)]
        public DateTime $created_at,
        public ?string $request_id,
        public DebugItemType $type,
        public QueryDebugItem|LogDebugItem|ExceptionDebugItem $data,
    ) {}
}
