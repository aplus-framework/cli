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

use Framework\CLI\Command;
use Framework\CLI\Streams\Stdout;
use PHPUnit\Framework\TestCase;

final class ConsoleTest extends TestCase
{
    protected ConsoleMock $console;

    protected function setUp() : void
    {
        Stdout::init();
        $this->console = new ConsoleMock();
    }

    protected function tearDown() : void
    {
        Stdout::reset();
    }

    public function testEmptyLine() : void
    {
        $this->console->prepare([
            'file.php',
        ]);
        self::assertSame('', $this->console->command);
        self::assertSame([], $this->console->getOptions());
        self::assertSame([], $this->console->getArguments());
    }

    public function testCommandLine() : void
    {
        $this->console->prepare([
            'file.php',
            'command',
        ]);
        self::assertSame('command', $this->console->command);
        self::assertSame([], $this->console->getOptions());
        self::assertSame([], $this->console->getArguments());
        $this->console->prepare([
            'file.php',
            'xx',
        ]);
        self::assertSame('xx', $this->console->command);
        self::assertSame([], $this->console->getOptions());
        self::assertSame([], $this->console->getArguments());
    }

    public function testOptionsLine() : void
    {
        $this->console->prepare([
            'file.php',
            'command',
            '-x',
            '-short',
            '--long',
            '--long-value=10',
            '-y=10',
        ]);
        self::assertSame('command', $this->console->command);
        self::assertSame([
            'x' => true,
            's' => true,
            'h' => true,
            'o' => true,
            'r' => true,
            't' => true,
            'long' => true,
            'long-value' => '10',
            'y' => true,
            '=' => true,
            1 => true,
            0 => true,
        ], $this->console->getOptions());
        self::assertSame([], $this->console->getArguments());
    }

    public function testArgumentsLine() : void
    {
        $this->console->prepare([
            'file.php',
            'command',
            'z',
            '-a',
            'x',
        ]);
        self::assertSame([
            'a' => true,
        ], $this->console->getOptions());
        self::assertSame([
            'z',
            'x',
        ], $this->console->getArguments());
        $this->console->prepare([
            'file.php',
            'command',
            '--',
            'z',
            '-a',
            'x',
        ]);
        self::assertSame([], $this->console->getOptions());
        self::assertSame([
            'z',
            '-a',
            'x',
        ], $this->console->getArguments());
        $this->console->prepare([
            'file.php',
            'command',
            '-i',
            '-j',
            '--',
            'z',
            '-a',
            'x',
        ]);
        self::assertSame(['i' => true, 'j' => true], $this->console->getOptions());
        self::assertSame([
            'z',
            '-a',
            'x',
        ], $this->console->getArguments());
    }

    public function testCommands() : void
    {
        self::assertEmpty($this->console->getCommands());
        $command = new class($this->console) extends CommandMock {
            protected bool $active = false;
        };
        $this->console->addCommand($command);
        self::assertEmpty($this->console->getCommands());
        $command = new CommandMock($this->console);
        $this->console->addCommands([$command]);
        self::assertNotEmpty($this->console->getCommands());
        self::assertInstanceOf(Command::class, $this->console->getCommand('test'));
    }

    public function testCommandString() : void
    {
        self::assertEmpty($this->console->getCommands());
        $this->console->addCommands([
            CommandMock::class,
        ]);
        self::assertNotEmpty($this->console->getCommands());
    }

    public function testCommandIndex() : void
    {
        $this->console->run();
        self::assertStringContainsString('index', Stdout::getContents());
    }

    public function _testCommandNotFound() : void
    {
        // TODO: Exit breaks the test
        $this->console->prepare(['file.php', 'unknown']);
        $this->console->run();
    }

    public function testRun() : void
    {
        $this->console->prepare([
            'file.php',
            'test',
            '--option=foo',
            '-o',
            'argument0',
            'argument1',
        ]);
        $this->console->addCommand(new CommandMock($this->console));
        $this->console->run();
        self::assertSame($this->getContentsOfCommandMock(), Stdout::getContents());
    }

    protected function getContentsOfCommandMock() : string
    {
        return \print_r(['option' => 'foo', 'o' => 1], true) . \PHP_EOL
            . \print_r(1, true) . \PHP_EOL
            . \print_r(['argument0', 'argument1'], true) . \PHP_EOL
            . \print_r('argument1', true) . \PHP_EOL;
    }

    public function testExec() : void
    {
        $this->console->addCommand(new CommandMock($this->console));
        $this->console->exec('test --option=foo -o argument0 argument1');
        self::assertSame($this->getContentsOfCommandMock(), Stdout::getContents());
    }

    public function testCommandToArgs() : void
    {
        self::assertSame(
            [
            'command',
            '--one=two',
            '--three=four',
            'can I have a "little" more',
        ],
            $this->console::commandToArgs(
                'command --one=two   --three="four" \'can I have a "little" more\''
            )
        );
    }
}
