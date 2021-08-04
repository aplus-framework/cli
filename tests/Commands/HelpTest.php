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

final class HelpTest extends TestCase
{
    public function testHelp() : void
    {
        $console = new Console();
        $console->addCommand(Foo::class);
        Stdout::init();
        $console->exec('help');
        self::assertStringContainsString('Command', Stdout::getContents());
        self::assertStringNotContainsString('Options', Stdout::getContents());
        Stdout::reset();
        $console->exec('help foo');
        self::assertStringContainsString('Options', Stdout::getContents());
    }
}
