<?php

namespace Tempest\Debug\Tests\Fixtures;

use Exception;
use Throwable;

final class ThrowAndReturnException
{
    public static function getThrowable(): Throwable
    {
        try {
            throw new Exception();
        } catch (Exception $exception) {
            return $exception;
        }
    }
}
