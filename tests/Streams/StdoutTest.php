<?php
/*
 * This file is part of Aplus Framework CLI Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\CLI\Streams;

use Framework\CLI\Streams\Stdout;
use PHPUnit\Framework\TestCase;

/**
 * @runTestsInSeparateProcesses
 */
final class StdoutTest extends TestCase
{
    public function testStream() : void
    {
        Stdout::init();
        self::assertSame('', Stdout::getContents());
        \fwrite(\STDOUT, 'foo');
        self::assertSame('foo', Stdout::getContents());
        \fwrite(\STDOUT, 'bar');
        self::assertSame('foobar', Stdout::getContents());
        Stdout::reset();
        self::assertSame('', Stdout::getContents());
    }
}
