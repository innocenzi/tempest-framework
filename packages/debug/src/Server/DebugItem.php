<?php

namespace Tempest\Debug\Server;

use Tempest\DateTime\DateTime;
use Tempest\DateTime\FormatPattern;
use Tempest\Debug\Backtrace\Backtrace;
use Tempest\Validation\Rules\DateTimeFormat;

final class DebugItem
{
    public function __construct(
        public ?int $id,
        #[DateTimeFormat(FormatPattern::JAVASCRIPT)]
        public DateTime $created_at,
        public ?string $request_id,
        public DebugItemType $type,
        public string $data,
        public Backtrace $backtrace,
    ) {}
}
