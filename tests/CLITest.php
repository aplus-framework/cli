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
		Stream::reset();
		CLI::write('Hello!', CLI::FG_RED);
		$this->assertStringContainsString("\033[0;31mHello!", Stream::getOutput());
		Stream::reset();
		CLI::write('Hello!', null, null, 2);
		$this->assertEquals("He\nll\no!\n", Stream::getOutput());
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
		$line = [];
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
		CLI::table([[1, 'John'], [2, 'Mary']]);
		$table = <<<EOL
+---+------+
| 1 | John |
| 2 | Mary |
+---+------+

EOL;
		$this->assertEquals($table, Stream::getOutput());
		Stream::reset();
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

	public function testStyle()
	{
		$this->assertEquals("foo\033[0m", CLI::style('foo'));
		$this->assertEquals(
			"\033[0;31mfoo\033[0m",
			CLI::style('foo', CLI::FG_RED)
		);
		$this->assertEquals(
			"\033[0;31m\033[44mfoo\033[0m",
			CLI::style('foo', CLI::FG_RED, CLI::BG_BLUE)
		);
		$this->assertEquals(
			"\033[0;31m\033[44m\033[1m\033[3mfoo\033[0m",
			CLI::style('foo', CLI::FG_RED, CLI::BG_BLUE, [CLI::FM_BOLD, CLI::FM_ITALIC])
		);
	}

	public function testStyleWithInvalidColor()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Invalid color: bar');
		CLI::style('foo', 'bar');
	}

	public function testStyleWithInvalidBackground()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Invalid background color: baz');
		CLI::style('foo', null, 'baz');
	}

	public function testStyleWithInvalidFormat()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Invalid format: bar');
		CLI::style('foo', null, null, [CLI::FM_BOLD, 'bar']);
	}

	public function testBox()
	{
		CLI::box('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam'
			. ' sem lacus, rutrum vel neque eu, aliquam aliquet neque.');
		$this->assertStringContainsString('Lorem', Stream::getOutput());
	}
}
