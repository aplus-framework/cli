<?php
/*
 * This file is part of Aplus Framework CLI Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\CLI;

use Framework\CLI\Console;
use PHPUnit\Framework\TestCase;

final class CommandTest extends TestCase
{
    protected CommandMock $command;

    protected function setUp() : void
    {
        $this->command = new CommandMock(new Console());
    }

    public function testConsole() : void
    {
        self::assertInstanceOf(Console::class, $this->command->getConsole());
        $console = new Console();
        $this->command->setConsole($console);
        self::assertSame($console, $this->command->getConsole());
    }

    public function testName() : void
    {
        self::assertSame('test', $this->command->getName());
        $this->command->setName('Foo');
        self::assertSame('Foo', $this->command->getName());
    }

    public function testDescription() : void
    {
        self::assertSame('Lorem ipsum', $this->command->getDescription());
        $this->command->setDescription('Foo bar.');
        self::assertSame('Foo bar.', $this->command->getDescription());
    }

    public function testUsage() : void
    {
        self::assertSame('test', $this->command->getUsage());
        $this->command->setUsage('foo');
        self::assertSame('foo', $this->command->getUsage());
    }

    public function testOptions() : void
    {
        self::assertSame(['-b' => 'foo bar'], $this->command->getOptions());
        $this->command->setOptions(['-a' => 'baz']);
        self::assertSame(['-a' => 'baz'], $this->command->getOptions());
    }

    public function testActive() : void
    {
        self::assertTrue($this->command->isActive());
        $this->command->deactivate();
        self::assertFalse($this->command->isActive());
        $this->command->activate();
        self::assertTrue($this->command->isActive());
    }
}
