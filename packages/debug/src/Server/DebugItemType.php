<?php

namespace Tempest\Debug\Server;

enum DebugItemType: string
{
    case LOG = 'log';
    case QUERY = 'query';
    case EXCEPTION = 'exception';
}
