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
		self::assertSame("Hello!\n", Stream::getOutput());
		Stream::reset();
		CLI::write('Hello!', CLI::FG_RED);
		self::assertStringContainsString("\033[0;31mHello!", Stream::getOutput());
		Stream::reset();
		CLI::write('Hello!', null, null, 2);
		self::assertSame("He\nll\no!\n", Stream::getOutput());
	}

	public function testBeep() : void
	{
		CLI::beep(2);
		self::assertSame("\x07\x07", Stream::getOutput());
	}

	public function testNewLine() : void
	{
		CLI::newLine(2);
		self::assertSame(\PHP_EOL . \PHP_EOL, Stream::getOutput());
	}

	public function testLiveLine() : void
	{
		for ($i = 0; $i <= 10; $i++) {
			$percent = $i * 10 . '%';
			//$percent = \str_pad($percent, 5, ' ', \STR_PAD_LEFT);
			$progress = '';
			//$progress = \str_repeat('#', $i);
			//$progress = \str_pad($progress, 10);
			$finalize = $i === 10;
			CLI::liveLine($progress . $percent, $finalize);
			//\sleep(1);
			if ($finalize) {
				$percent .= \PHP_EOL;
			}
			self::assertSame("\33[2K\r{$percent}", Stream::getOutput());
			Stream::reset();
		}
	}

	public function testIsWindows() : void
	{
		self::assertFalse(CLI::isWindows());
	}

	public function testWidth() : void
	{
		self::assertSame(80, CLI::getWidth());
	}

	public function testWrap() : void
	{
		$line = [];
		$line[0] = \str_repeat('a', 80);
		$line[1] = \str_repeat('a', 80);
		$line[2] = \str_repeat('a', 80);
		self::assertSame(
			$line[0] . \PHP_EOL . $line[1] . \PHP_EOL . $line[2],
			CLI::wrap(\implode($line))
		);
	}

	public function testClear() : void
	{
		CLI::clear();
		self::assertSame("\e[H\e[2J", Stream::getOutput());
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
		self::assertSame($table, Stream::getOutput());
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
		self::assertSame($table, Stream::getOutput());
	}

	public function testStyle() : void
	{
		self::assertSame("foo\033[0m", CLI::style('foo'));
		self::assertSame(
			"\033[0;31mfoo\033[0m",
			CLI::style('foo', CLI::FG_RED)
		);
		self::assertSame(
			"\033[0;31m\033[44mfoo\033[0m",
			CLI::style('foo', CLI::FG_RED, CLI::BG_BLUE)
		);
		self::assertSame(
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
		self::assertStringContainsString('Lorem', Stream::getOutput());
	}
}
