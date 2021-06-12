<?php namespace Tests\CLI;

use Framework\CLI\Command;
use Framework\CLI\Stream;
use PHPUnit\Framework\TestCase;

final class ConsoleTest extends TestCase
{
	protected ConsoleMock $console;

	protected function setUp() : void
	{
		Stream::init();
		global $argv;
		$argv = [];
		$this->console = new ConsoleMock();
	}

	protected function tearDown() : void
	{
		Stream::reset();
	}

	public function testEmptyLine() : void
	{
		$this->console->prepare([
			'file.php',
		]);
		$this->assertEquals('', $this->console->command);
		$this->assertEquals([], $this->console->getOptions());
		$this->assertEquals([], $this->console->getArguments());
	}

	public function testCommandLine() : void
	{
		$this->console->prepare([
			'file.php',
			'command',
		]);
		$this->assertEquals('command', $this->console->command);
		$this->assertEquals([], $this->console->getOptions());
		$this->assertEquals([], $this->console->getArguments());
		$this->console->prepare([
			'file.php',
			'xx',
		]);
		$this->assertEquals('xx', $this->console->command);
		$this->assertEquals([], $this->console->getOptions());
		$this->assertEquals([], $this->console->getArguments());
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
		$this->assertEquals('command', $this->console->command);
		$this->assertEquals([
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
		$this->assertEquals([], $this->console->getArguments());
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
		$this->assertEquals([
			'a' => true,
		], $this->console->getOptions());
		$this->assertEquals([
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
		$this->assertEquals([], $this->console->getOptions());
		$this->assertEquals([
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
		$this->assertEquals(['i' => true, 'j' => true], $this->console->getOptions());
		$this->assertEquals([
			'z',
			'-a',
			'x',
		], $this->console->getArguments());
	}

	public function testCommands() : void
	{
		$this->assertEmpty($this->console->getCommands());
		$command = new class($this->console) extends CommandMock {
			protected bool $active = false;
		};
		$this->console->addCommand($command);
		$this->assertEmpty($this->console->getCommands());
		$command = new CommandMock($this->console);
		$this->console->addCommands([$command]);
		$this->assertNotEmpty($this->console->getCommands());
		$this->assertInstanceOf(Command::class, $this->console->getCommand('test'));
	}

	public function testCommandString() : void
	{
		$this->assertEmpty($this->console->getCommands());
		$this->console->addCommands([
			CommandMock::class,
		]);
		$this->assertNotEmpty($this->console->getCommands());
	}

	public function testCommandIndex() : void
	{
		$this->console->run();
		$this->assertStringContainsString('index', Stream::getOutput());
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
		$this->assertEquals($this->getOutputOfCommandMock(), Stream::getOutput());
	}

	protected function getOutputOfCommandMock() : string
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
		$this->assertEquals($this->getOutputOfCommandMock(), Stream::getOutput());
	}

	public function testCommandToArgs() : void
	{
		$this->assertEquals(
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
