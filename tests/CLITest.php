<?php namespace Tests\CLI;

use Framework\CLI\CLI;
use Framework\CLI\Stream;
use PHPUnit\Framework\TestCase;

final class CLITest extends TestCase
{
	protected function setUp() : void
	{
		Stream::init();
	}

	protected function tearDown() : void
	{
		Stream::reset();
	}

	public function testWrite() : void
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

	public function testBeep() : void
	{
		CLI::beep(2);
		$this->assertEquals("\x07\x07", Stream::getOutput());
	}

	public function testNewLine() : void
	{
		CLI::newLine(2);
		$this->assertEquals(\PHP_EOL . \PHP_EOL, Stream::getOutput());
	}

	public function testIsWindows() : void
	{
		$this->assertFalse(CLI::isWindows());
	}

	public function testWidth() : void
	{
		$this->assertEquals(80, CLI::getWidth());
	}

	public function testWrap() : void
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

	public function testClear() : void
	{
		CLI::clear();
		$this->assertEquals("\e[H\e[2J", Stream::getOutput());
	}

	public function testTable() : void
	{
		CLI::table([[1, 'John'], [2, 'Mary']]);
		$table = <<<'EOL'
			+---+------+
			| 1 | John |
			| 2 | Mary |
			+---+------+

			EOL;
		$this->assertEquals($table, Stream::getOutput());
		Stream::reset();
		CLI::table([[1, 'John'], [2, 'Mary']], ['ID', 'Name']);
		$table = <<<'EOL'
			+----+------+
			| ID | Name |
			+----+------+
			| 1  | John |
			| 2  | Mary |
			+----+------+

			EOL;
		$this->assertEquals($table, Stream::getOutput());
	}

	public function testStyle() : void
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

	public function testStyleWithInvalidColor() : void
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Invalid color: bar');
		CLI::style('foo', 'bar');
	}

	public function testStyleWithInvalidBackground() : void
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Invalid background color: baz');
		CLI::style('foo', null, 'baz');
	}

	public function testStyleWithInvalidFormat() : void
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Invalid format: bar');
		CLI::style('foo', null, null, [CLI::FM_BOLD, 'bar']);
	}

	public function testBox() : void
	{
		CLI::box('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam'
			. ' sem lacus, rutrum vel neque eu, aliquam aliquet neque.');
		$this->assertStringContainsString('Lorem', Stream::getOutput());
	}
}
