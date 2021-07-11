<?php
/*
 * This file is part of The Framework CLI Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\CLI;

use Framework\CLI\Stream;
use PHPUnit\Framework\TestCase;

final class StreamTest extends TestCase
{
    public function testStream() : void
    {
        Stream::init();
        self::assertSame('', Stream::getOutput());
        \fwrite(\STDOUT, 'foo');
        self::assertSame('foo', Stream::getOutput());
        \fwrite(\STDOUT, 'bar');
        self::assertSame('foobar', Stream::getOutput());
        Stream::reset();
        self::assertSame('', Stream::getOutput());
    }
}
