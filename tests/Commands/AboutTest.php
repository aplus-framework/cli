<?php
/*
 * This file is part of Aplus Framework CLI Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\CLI\Commands;

use Framework\CLI\Console;
use Framework\CLI\Stream;
use PHPUnit\Framework\TestCase;

final class AboutTest extends TestCase
{
    public function testAbout() : void
    {
        $console = new Console();
        Stream::init();
        $console->exec('about');
        self::assertStringContainsString('About', Stream::getOutput());
        self::assertStringContainsString('CLI', Stream::getOutput());
    }
}
