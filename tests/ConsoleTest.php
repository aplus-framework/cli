<?php namespace Tests\CLI;

use Framework\CLI\Command;
use PHPUnit\Framework\TestCase;

class ConsoleTest extends TestCase
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

	protected function setArgv(array $args)
	{
		global $argv;
		$argv = $args;
		$this->console->prepare();
	}

	public function testEmptyLine()
	{
		$this->setArgv([
			'file.php',
		]);
		$this->assertEquals('', $this->console->command);
		$this->assertEquals([], $this->console->getOptions());
		$this->assertEquals([], $this->console->getArguments());
	}

	public function testCommandLine()
	{
		$this->setArgv([
			'file.php',
			'command',
		]);
		$this->assertEquals('command', $this->console->command);
		$this->assertEquals([], $this->console->getOptions());
		$this->assertEquals([], $this->console->getArguments());
		$this->setArgv([
			'file.php',
			'xx',
		]);
		$this->assertEquals('xx', $this->console->command);
		$this->assertEquals([], $this->console->getOptions());
		$this->assertEquals([], $this->console->getArguments());
	}

	public function testOptionsLine()
	{
		$this->setArgv([
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

	public function testArgumentsLine()
	{
		$this->setArgv([
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
		$this->setArgv([
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
		$this->setArgv([
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

	public function testCommands()
	{
		$this->assertEmpty($this->console->getCommands());
		$command = new class($this->console) extends CommandMock {
			protected bool $active = false;
		};
		$this->console->addCommand($command);
		$this->assertEmpty($this->console->getCommands());
		$command = new CommandMock($this->console);
		$this->console->addCommand($command);
		$this->assertNotEmpty($this->console->getCommands());
		$this->assertInstanceOf(Command::class, $this->console->getCommand('test'));
	}

	public function testCommandIndex()
	{
		$this->console->run();
		$this->assertStringContainsString('index', Stream::$output);
	}

	public function _testCommandNotFound()
	{
		// TODO: Exit breaks the test
		$this->setArgv(['file.php', 'unknown']);
		$this->console->run();
	}

	public function testRun()
	{
		$this->setArgv([
			'file.php',
			'test',
			'--option=foo',
			'-o',
			'argument0',
			'argument1',
		]);
		$this->console->addCommand(new CommandMock($this->console));
		$this->console->run();
		$this->assertEquals(
			\print_r(['option' => 'foo', 'o' => 1], true) . \PHP_EOL
			. \print_r(1, true) . \PHP_EOL
			. \print_r(['argument0', 'argument1'], true) . \PHP_EOL
			. \print_r('argument1', true) . \PHP_EOL,
			Stream::$output
		);
	}
}
