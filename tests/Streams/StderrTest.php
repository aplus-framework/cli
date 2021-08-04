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

use Framework\CLI\Streams\Stderr;
use PHPUnit\Framework\TestCase;

/**
 * @runTestsInSeparateProcesses
 */
final class StderrTest extends TestCase
{
    public function testStream() : void
    {
        Stderr::init();
        self::assertSame('', Stderr::getContents());
        \fwrite(\STDERR, 'errr');
        self::assertSame('errr', Stderr::getContents());
        \fwrite(\STDERR, 'bar');
        self::assertSame('errrbar', Stderr::getContents());
        Stderr::reset();
        self::assertSame('', Stderr::getContents());
    }
}
