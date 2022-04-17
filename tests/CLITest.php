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

use Framework\CLI\CLI;
use Framework\CLI\Streams\Stderr;
use Framework\CLI\Streams\Stdout;
use PHPUnit\Framework\TestCase;

final class CLITest extends TestCase
{
    protected function setUp() : void
    {
        Stdout::init();
    }

    protected function tearDown() : void
    {
        Stdout::reset();
    }

    public function testWrite() : void
    {
        CLI::write('Hello!');
        self::assertSame("Hello!\n", Stdout::getContents());
        Stdout::reset();
        CLI::write('Hello!', CLI::FG_RED);
        self::assertStringContainsString("\033[0;31mHello!", Stdout::getContents());
        Stdout::reset();
        CLI::write('Hello!', null, null, 2);
        self::assertSame("He\nll\no!\n", Stdout::getContents());
    }

    public function testBeep() : void
    {
        CLI::beep(2);
        self::assertSame("\x07\x07", Stdout::getContents());
    }

    public function testNewLine() : void
    {
        CLI::newLine(2);
        self::assertSame(\PHP_EOL . \PHP_EOL, Stdout::getContents());
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
            self::assertSame("\33[2K\r{$percent}", Stdout::getContents());
            Stdout::reset();
        }
    }

    public function testIsWindows() : void
    {
        self::assertFalse(CLI::isWindows());
    }

    public function testWidth() : void
    {
        self::assertSame(80, CLI::getWidth());
        $cli = new class() extends CLI {
            public static function isWindows() : bool
            {
                return true;
            }
        };
        self::assertSame(100, $cli::getWidth(100));
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
        self::assertSame("\e[H\e[2J", Stdout::getContents());
    }

    public function testError() : void
    {
        Stderr::init();
        CLI::error('Whoops!', null);
        self::assertStringContainsString('Whoops!', Stderr::getContents());
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
        self::assertSame($table, Stdout::getContents());
        Stdout::reset();
        CLI::table([[1, 'John'], [2, 'Mary']], ['ID', 'Name']);
        $table = <<<'EOL'
            +----+------+
            | ID | Name |
            +----+------+
            | 1  | John |
            | 2  | Mary |
            +----+------+

            EOL;
        self::assertSame($table, Stdout::getContents());
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

    public function testConstantsWithStyle() : void
    {
        $class = new \ReflectionClass(CLI::class);
        foreach ($class->getReflectionConstants(\ReflectionClassConstant::IS_PUBLIC) as $constant) {
            if (\str_starts_with($constant->getName(), 'BG_')) {
                self::assertNotEmpty(CLI::style('', background: $constant->getValue()));
                continue;
            }
            if (\str_starts_with($constant->getName(), 'FG_')) {
                self::assertNotEmpty(CLI::style('', color: $constant->getValue()));
                continue;
            }
            if (\str_starts_with($constant->getName(), 'FM_')) {
                self::assertNotEmpty(CLI::style('', formats: [$constant->getValue()]));
            }
        }
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
        self::assertStringContainsString('Lorem', Stdout::getContents());
    }
}
