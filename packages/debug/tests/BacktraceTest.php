<?php

namespace Tempest\Debug\Tests;

use PHPUnit\Framework\TestCase;
use Tempest\Debug\Backtrace\Backtrace;
use Tempest\Debug\Tests\Fixtures\ThrowAndReturnException;
use Tempest\Debug\Tests\Fixtures\TraceArguments;

final class BacktraceTest extends TestCase
{
    public function test_basic_backtrace(): void
    {
        $backtrace = Backtrace::create();

        $this->assertEquals(__LINE__ - 2, $backtrace->frames[0]->line);
        $this->assertEquals(__FILE__, $backtrace->frames[0]->file);
        $this->assertEquals(static::class, $backtrace->frames[0]->class);
        $this->assertEquals(explode('::', __METHOD__)[1], $backtrace->frames[0]->method);
    }

    public function test_backtrace_from_throwable(): void
    {
        $throwable = ThrowAndReturnException::getThrowable();
        $backtrace = Backtrace::fromThrowable($throwable);

        $this->assertEquals(13, $backtrace->frames[0]->line);
        $this->assertEquals(ThrowAndReturnException::class, $backtrace->frames[0]->class);
        $this->assertEquals('getThrowable', $backtrace->frames[0]->method);
    }

    public function test_can_include_instance(): void
    {
        $backtrace = Backtrace::create(instance: true);

        $this->assertInstanceOf(self::class, $backtrace->frames[0]->instance);
    }

    public function test_can_include_arguments(): void
    {
        $backtrace = $this->backtraceWithArguments('hello world', bool: true);

        $this->assertSame(['hello world', true], $backtrace->frames[0]->arguments);
    }

    public function test_can_include_arguments_names(): void
    {
        $backtrace = $this->backtraceWithArgumentNames('hello world', bool: true);

        $this->assertSame([
            'string' => 'hello world',
            'bool' => true,
        ], $backtrace->frames[0]->arguments);
    }

    private function backtraceWithArgumentNames(string $string, bool $bool): Backtrace
    {
        return Backtrace::create(arguments: true, instance: true);
    }

    private function backtraceWithArguments(string $string, bool $bool): Backtrace
    {
        return Backtrace::create(arguments: true);
    }
}
