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

final class HelpTest extends TestCase
{
    public function testHelp() : void
    {
        $console = new Console();
        $console->addCommand(Foo::class);
        Stream::init();
        $console->exec('help');
        self::assertStringContainsString('Command', Stream::getOutput());
        self::assertStringNotContainsString('Options', Stream::getOutput());
        Stream::reset();
        $console->exec('help foo');
        self::assertStringContainsString('Options', Stream::getOutput());
    }
}
