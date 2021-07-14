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

    public function testDescription() : void
    {
        self::assertSame('Lorem ipsum', $this->command->getDescription());
    }

    public function testUsage() : void
    {
        self::assertSame('test', $this->command->getUsage());
    }

    public function testOptions() : void
    {
        self::assertSame(['-b' => 'foo bar'], $this->command->getOptions());
    }
}
