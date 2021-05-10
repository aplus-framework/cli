<?php namespace Tests\CLI;

use Framework\CLI\CLI;
use Framework\CLI\Stream;
use PHPUnit\Framework\TestCase;

class CLITest extends TestCase
{
	protected function setUp() : void
	{
		Stream::init();
	}

	protected function tearDown() : void
	{
		Stream::reset();
	}

	public function testWrite()
	{
		CLI::write('Hello!');
		$this->assertEquals("Hello!\n", Stream::getOutput());
	}

	public function testBeep()
	{
		CLI::beep(2);
		$this->assertEquals("\x07\x07", Stream::getOutput());
	}

	public function testNewLine()
	{
		CLI::newLine(2);
		$this->assertEquals(\PHP_EOL . \PHP_EOL, Stream::getOutput());
	}

	public function testIsWindows()
	{
		$this->assertFalse(CLI::isWindows());
	}

	public function testWidth()
	{
		$this->assertEquals(80, CLI::getWidth());
	}

	public function testWrap()
	{
		$line[0] = \str_repeat('a', 80);
		$line[1] = \str_repeat('a', 80);
		$line[2] = \str_repeat('a', 80);
		$this->assertEquals(
			$line[0] . \PHP_EOL . $line[1] . \PHP_EOL . $line[2],
			CLI::wrap(\implode($line))
		);
	}

	public function testClear()
	{
		CLI::clear();
		$this->assertEquals("\e[H\e[2J", Stream::getOutput());
	}

	public function testTable()
	{
		CLI::table([[1, 'John'], [2, 'Mary']], ['ID', 'Name']);
		$table = <<<EOL
+----+------+
| ID | Name |
+----+------+
| 1  | John |
| 2  | Mary |
+----+------+

EOL;
		$this->assertEquals($table, Stream::getOutput());
	}
}
