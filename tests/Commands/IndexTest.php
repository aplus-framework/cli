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

final class IndexTest extends TestCase
{
    public function testIndex() : void
    {
        $console = new Console();
        Stdout::init();
        $console->exec('index');
        self::assertStringContainsString('Commands', Stdout::getContents());
    }

    public function testHelp() : void
    {
        $console = new Console();
        Stdout::init();
        $console->exec('help index');
        self::assertStringContainsString('Shows greeting', Stdout::getContents());
    }

    public function testOptionGreet() : void
    {
        $console = new Console();
        Stdout::init();
        $console->exec('index -g');
        self::assertStringContainsString('Good ', Stdout::getContents());
    }

    public function testManyGroups() : void
    {
        $console = new Console();
        $foo = new Foo();
        $foo->setName('fooCommand')->setGroup('Group 2');
        $bar = new Foo();
        $bar->setName('barCommand')->setGroup('Group 1');
        $console->addCommand($foo)->addCommand($bar);
        Stdout::init();
        $console->exec('index');
        self::assertStringContainsString('Group 2', Stdout::getContents());
        self::assertStringContainsString('Group 1', Stdout::getContents());
        $group2Pos = \strpos(Stdout::getContents(), 'Group 2');
        $group1Pos = \strpos(Stdout::getContents(), 'Group 1');
        self::assertTrue($group2Pos > $group1Pos);
    }
}
