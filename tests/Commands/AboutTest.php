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
use Framework\CLI\Streams\Stdout;
use PHPUnit\Framework\TestCase;

final class AboutTest extends TestCase
{
    public function testAbout() : void
    {
        $console = new Console();
        Stdout::init();
        $console->exec('about');
        self::assertStringContainsString('About', Stdout::getContents());
        self::assertStringContainsString('CLI', Stdout::getContents());
    }
}
