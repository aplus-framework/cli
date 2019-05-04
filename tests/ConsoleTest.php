<?php namespace Tests\CLI;

use Framework\CLI\Command;
use PHPUnit\Framework\TestCase;

class ConsoleTest extends TestCase
{
	/**
	 * @var ConsoleMock
	 */
	protected $console;

	protected function setUp()
	{
		Stream::init();
		global $argv;
		$argv = [];
		$this->console = new ConsoleMock();
	}

	protected function tearDown()
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
		$this->assertEquals([], $this->console->options);
		$this->assertEquals([], $this->console->arguments);
	}

	public function testCommandLine()
	{
		$this->setArgv([
			'file.php',
			'command',
		]);
		$this->assertEquals('command', $this->console->command);
		$this->assertEquals([], $this->console->options);
		$this->assertEquals([], $this->console->arguments);
		$this->setArgv([
			'file.php',
			'xx',
		]);
		$this->assertEquals('xx', $this->console->command);
		$this->assertEquals([], $this->console->options);
		$this->assertEquals([], $this->console->arguments);
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
		], $this->console->options);
		$this->assertEquals([], $this->console->arguments);
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
		], $this->console->options);
		$this->assertEquals([
			'z',
			'x',
		], $this->console->arguments);
		$this->setArgv([
			'file.php',
			'command',
			'--',
			'z',
			'-a',
			'x',
		]);
		$this->assertEquals([], $this->console->options);
		$this->assertEquals([
			'z',
			'-a',
			'x',
		], $this->console->arguments);
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
		$this->assertEquals(['i' => true, 'j' => true], $this->console->options);
		$this->assertEquals([
			'z',
			'-a',
			'x',
		], $this->console->arguments);
	}

	public function testCommands()
	{
		$this->assertEmpty($this->console->getCommands());
		$this->console->addCommand(new CommandMock($this->console));
		$this->assertNotEmpty($this->console->getCommands());
		$this->assertInstanceOf(Command::class, $this->console->getCommand('test'));
	}

	public function testRun()
	{
		$this->setArgv([
			'file.php',
			'test',
			'--option=foo',
			'argument',
			'argument',
		]);
		$this->console->addCommand(new CommandMock($this->console));
		$this->console->run();
		$this->assertEquals(
			Stream::$output,
			\print_r(['option' => 'foo'], true) . \PHP_EOL
			. \print_r(['argument', 'argument'], true) . \PHP_EOL
		);
	}
}
