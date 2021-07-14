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

use Framework\CLI\Command;
use Framework\CLI\Console;
use PHPUnit\Framework\TestCase;

class NoPropertiesCommandTest extends TestCase
{
    protected Command $command;

    protected function setUp() : void
    {
        $this->command = new NoPropertiesCommand(new Console());
    }

    public function testAutoName() : void
    {
        self::assertSame('noproperties', $this->command->getName());
    }

    public function testAutoDescription() : void
    {
        self::assertSame(
            'This command does not provide a description.',
            $this->command->getDescription()
        );
    }
}
