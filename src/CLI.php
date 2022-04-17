<?php declare(strict_types=1);
/*
 * This file is part of Aplus Framework CLI Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Framework\CLI;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;

/**
 * Class CLI.
 *
 * @see https://en.wikipedia.org/wiki/ANSI_escape_code
 *
 * @package cli
 */
class CLI
{
    /**
     * Background color "black".
     *
     * @var string
     */
    public const BG_BLACK = 'black';
    /**
     * Background color "red".
     *
     * @var string
     */
    public const BG_RED = 'red';
    /**
     * Background color "green".
     *
     * @var string
     */
    public const BG_GREEN = 'green';
    /**
     * Background color "yellow".
     *
     * @var string
     */
    public const BG_YELLOW = 'yellow';
    /**
     * Background color "blue".
     *
     * @var string
     */
    public const BG_BLUE = 'blue';
    /**
     * Background color "magenta".
     *
     * @var string
     */
    public const BG_MAGENTA = 'magenta';
    /**
     * Background color "cyan".
     *
     * @var string
     */
    public const BG_CYAN = 'cyan';
    /**
     * Background color "white".
     *
     * @var string
     */
    public const BG_WHITE = 'white';
    /**
     * Background color "bright black".
     *
     * @var string
     */
    public const BG_BRIGHT_BLACK = 'bright_black';
    /**
     * Background color "bright red".
     *
     * @var string
     */
    public const BG_BRIGHT_RED = 'bright_red';
    /**
     * Background color "bright green".
     *
     * @var string
     */
    public const BG_BRIGHT_GREEN = 'bright_green';
    /**
     * Background color "bright yellow".
     *
     * @var string
     */
    public const BG_BRIGHT_YELLOW = 'bright_yellow';
    /**
     * Background color "bright blue".
     *
     * @var string
     */
    public const BG_BRIGHT_BLUE = 'bright_blue';
    /**
     * Background color "bright magenta".
     *
     * @var string
     */
    public const BG_BRIGHT_MAGENTA = 'bright_magenta';
    /**
     * Background color "bright cyan".
     *
     * @var string
     */
    public const BG_BRIGHT_CYAN = 'bright_cyan';
    /**
     * Foreground color "black".
     *
     * @var string
     */
    public const FG_BLACK = 'black';
    /**
     * Foreground color "red".
     *
     * @var string
     */
    public const FG_RED = 'red';
    /**
     * Foreground color "green".
     *
     * @var string
     */
    public const FG_GREEN = 'green';
    /**
     * Foreground color "yellow".
     *
     * @var string
     */
    public const FG_YELLOW = 'yellow';
    /**
     * Foreground color "blue".
     *
     * @var string
     */
    public const FG_BLUE = 'blue';
    /**
     * Foreground color "magenta".
     *
     * @var string
     */
    public const FG_MAGENTA = 'magenta';
    /**
     * Foreground color "cyan".
     *
     * @var string
     */
    public const FG_CYAN = 'cyan';
    /**
     * Foreground color "white".
     *
     * @var string
     */
    public const FG_WHITE = 'white';
    /**
     * Foreground color "bright black".
     *
     * @var string
     */
    public const FG_BRIGHT_BLACK = 'bright_black';
    /**
     * Foreground color "bright red".
     *
     * @var string
     */
    public const FG_BRIGHT_RED = 'bright_red';
    /**
     * Foreground color "bright green".
     *
     * @var string
     */
    public const FG_BRIGHT_GREEN = 'bright_green';
    /**
     * Foreground color "bright yellow".
     *
     * @var string
     */
    public const FG_BRIGHT_YELLOW = 'bright_yellow';
    /**
     * Foreground color "bright blue".
     *
     * @var string
     */
    public const FG_BRIGHT_BLUE = 'bright_blue';
    /**
     * Foreground color "bright magenta".
     *
     * @var string
     */
    public const FG_BRIGHT_MAGENTA = 'bright_magenta';
    /**
     * Foreground color "bright cyan".
     *
     * @var string
     */
    public const FG_BRIGHT_CYAN = 'bright_cyan';
    /**
     * Foreground color "bright white".
     *
     * @var string
     */
    public const FG_BRIGHT_WHITE = 'bright_white';
    /**
     * SGR format "bold".
     *
     * @var string
     */
    public const FM_BOLD = 'bold';
    /**
     * SGR format "faint".
     *
     * @var string
     */
    public const FM_FAINT = 'faint';
    /**
     * SGR format "italic".
     *
     * @var string
     */
    public const FM_ITALIC = 'italic';
    /**
     * SGR format "underline".
     *
     * @var string
     */
    public const FM_UNDERLINE = 'underline';
    /**
     * SGR format "slow blink".
     *
     * @var string
     */
    public const FM_SLOW_BLINK = 'slow_blink';
    /**
     * SGR format "rapid blink".
     *
     * @var string
     */
    public const FM_RAPID_BLINK = 'rapid_blink';
    /**
     * SGR format "reverse video".
     *
     * @var string
     */
    public const FM_REVERSE_VIDEO = 'reverse_video';
    /**
     * SGR format "conceal".
     *
     * @var string
     */
    public const FM_CONCEAL = 'conceal';
    /**
     * SGR format "crossed out".
     *
     * @var string
     */
    public const FM_CROSSED_OUT = 'crossed_out';
    /**
     * SGR format "primary font".
     *
     * @var string
     */
    public const FM_PRIMARY_FONT = 'primary_font';
    /**
     * SGR format "fraktur".
     *
     * @var string
     */
    public const FM_FRAKTUR = 'fraktur';
    /**
     * SGR format "doubly underline".
     *
     * @var string
     */
    public const FM_DOUBLY_UNDERLINE = 'doubly_underline';
    /**
     * SGR format "encircled".
     *
     * @var string
     */
    public const FM_ENCIRCLED = 'encircled';
    /**
     * @var array<string,string>
     */
    protected static array $backgroundColors = [
        'black' => "\033[40m",
        'red' => "\033[41m",
        'green' => "\033[42m",
        'yellow' => "\033[43m",
        'blue' => "\033[44m",
        'magenta' => "\033[45m",
        'cyan' => "\033[46m",
        'white' => "\033[47m",
        'bright_black' => "\033[100m",
        'bright_red' => "\033[101m",
        'bright_green' => "\033[102m",
        'bright_yellow' => "\033[103m",
        'bright_blue' => "\033[104m",
        'bright_magenta' => "\033[105m",
        'bright_cyan' => "\033[106m",
        'bright_white' => "\033[107m",
    ];
    /**
     * @var array<string,string>
     */
    protected static array $foregroundColors = [
        'black' => "\033[0;30m",
        'red' => "\033[0;31m",
        'green' => "\033[0;32m",
        'yellow' => "\033[0;33m",
        'blue' => "\033[0;34m",
        'magenta' => "\033[0;35m",
        'cyan' => "\033[0;36m",
        'white' => "\033[0;37m",
        'bright_black' => "\033[0;90m",
        'bright_red' => "\033[0;91m",
        'bright_green' => "\033[0;92m",
        'bright_yellow' => "\033[0;93m",
        'bright_blue' => "\033[0;94m",
        'bright_magenta' => "\033[0;95m",
        'bright_cyan' => "\033[0;96m",
        'bright_white' => "\033[0;97m",
    ];
    /**
     * @var array<string,string>
     */
    protected static array $formats = [
        'bold' => "\033[1m",
        'faint' => "\033[2m",
        'italic' => "\033[3m",
        'underline' => "\033[4m",
        'slow_blink' => "\033[5m",
        'rapid_blink' => "\033[6m",
        'reverse_video' => "\033[7m",
        'conceal' => "\033[8m",
        'crossed_out' => "\033[9m",
        'primary_font' => "\033[10m",
        'fraktur' => "\033[20m",
        'doubly_underline' => "\033[21m",
        'encircled' => "\033[52m",
    ];
    protected static string $reset = "\033[0m";

    /**
     * Tells if it is running on a Windows OS.
     *
     * @return bool
     */
    #[Pure]
    public static function isWindows() : bool
    {
        return \PHP_OS_FAMILY === 'Windows';
    }

    /**
     * Get the screen width.
     *
     * @param int $default
     *
     * @return int
     */
    public static function getWidth(int $default = 80) : int
    {
        if (static::isWindows()) {
            return $default;
        }
        $width = (int) \shell_exec('tput cols');
        if ( ! $width) {
            return $default;
        }
        return $width;
    }

    /**
     * Displays text wrapped to a certain width.
     *
     * @param string $text
     * @param int|null $width
     *
     * @return string Returns the wrapped text
     */
    public static function wrap(string $text, int $width = null) : string
    {
        $width ??= static::getWidth();
        return \wordwrap($text, $width, \PHP_EOL, true);
    }

    /**
     * Calculate the multibyte length of a text without style characters.
     *
     * @param string $text The text being checked for length
     *
     * @return int
     */
    public static function strlen(string $text) : int
    {
        $codes = [];
        foreach (static::$foregroundColors as $color) {
            $codes[] = $color;
        }
        foreach (static::$backgroundColors as $background) {
            $codes[] = $background;
        }
        foreach (static::$formats as $format) {
            $codes[] = $format;
        }
        $codes[] = static::$reset;
        $text = \str_replace($codes, '', $text);
        return \mb_strlen($text);
    }

    /**
     * Applies styles to a text.
     *
     * @param string $text The text to be styled
     * @param string|null $color Foreground color. One of the FG_* constants
     * @param string|null $background Background color. One of the BG_* constants
     * @param array<int,string> $formats The text format. A list of FM_* constants
     *
     * @throws InvalidArgumentException For invalid color, background or format
     *
     * @return string Returns the styled text
     */
    public static function style(
        string $text,
        string $color = null,
        string $background = null,
        array $formats = []
    ) : string {
        $string = '';
        if ($color !== null) {
            if (empty(static::$foregroundColors[$color])) {
                throw new InvalidArgumentException('Invalid color: ' . $color);
            }
            $string = static::$foregroundColors[$color];
        }
        if ($background !== null) {
            if (empty(static::$backgroundColors[$background])) {
                throw new InvalidArgumentException('Invalid background color: ' . $background);
            }
            $string .= static::$backgroundColors[$background];
        }
        if ($formats) {
            foreach ($formats as $format) {
                if (empty(static::$formats[$format])) {
                    throw new InvalidArgumentException('Invalid format: ' . $format);
                }
                $string .= static::$formats[$format];
            }
        }
        $string .= $text . static::$reset;
        return $string;
    }

    /**
     * Write a text in the output.
     *
     * Optionally with styles and width wrapping.
     *
     * @param string $text The text to be written
     * @param string|null $color Foreground color. One of the FG_* constants
     * @param string|null $background Background color. One of the BG_* constants
     * @param int|null $width Width to wrap the text. Null to do not wrap.
     */
    public static function write(
        string $text,
        string $color = null,
        string $background = null,
        int $width = null
    ) : void {
        if ($width !== null) {
            $text = static::wrap($text, $width);
        }
        if ($color !== null || $background !== null) {
            $text = static::style($text, $color, $background);
        }
        \fwrite(\STDOUT, $text . \PHP_EOL);
    }

    /**
     * Prints a new line in the output.
     *
     * @param int $lines Number of lines to be printed
     */
    public static function newLine(int $lines = 1) : void
    {
        for ($i = 0; $i < $lines; $i++) {
            \fwrite(\STDOUT, \PHP_EOL);
        }
    }

    /**
     * Creates a "live line".
     *
     * Erase the current line, move the cursor to the beginning of the line and
     * writes a text.
     *
     * @param string $text The text to be written
     * @param bool $finalize If true the "live line" activity ends, creating a
     * new line after the text
     */
    public static function liveLine(string $text, bool $finalize = false) : void
    {
        // See: https://stackoverflow.com/a/35190285
        $string = '';
        if ( ! static::isWindows()) {
            $string .= "\33[2K";
        }
        $string .= "\r";
        $string .= $text;
        if ($finalize) {
            $string .= \PHP_EOL;
        }
        \fwrite(\STDOUT, $string);
    }

    /**
     * Performs audible beep alarms.
     *
     * @param int $times How many times should the beep be played
     * @param int $usleep Interval in microseconds
     */
    public static function beep(int $times = 1, int $usleep = 0) : void
    {
        for ($i = 0; $i < $times; $i++) {
            \fwrite(\STDOUT, "\x07");
            \usleep($usleep);
        }
    }

    /**
     * Writes a message box.
     *
     * @param array<int,string>|string $lines One line as string or multi-lines as array
     * @param string $background Background color. One of the BG_* constants
     * @param string $color Foreground color. One of the FG_* constants
     */
    public static function box(
        array | string $lines,
        string $background = CLI::BG_BLACK,
        string $color = CLI::FG_WHITE
    ) : void {
        $width = static::getWidth();
        $width -= 2;
        if ( ! \is_array($lines)) {
            $lines = [
                $lines,
            ];
        }
        $allLines = [];
        foreach ($lines as &$line) {
            $length = static::strlen($line);
            if ($length > $width) {
                $line = static::wrap($line, $width);
            }
            foreach (\explode(\PHP_EOL, $line) as $subLine) {
                $allLines[] = $subLine;
            }
        }
        unset($line);
        $blankLine = \str_repeat(' ', $width + 2);
        $text = static::style($blankLine, $color, $background);
        foreach ($allLines as $line) {
            $end = \str_repeat(' ', $width - static::strlen($line)) . ' ';
            $end = static::style($end, $color, $background);
            $text .= static::style(' ' . $line . $end, $color, $background);
        }
        $text .= static::style($blankLine, $color, $background);
        static::write($text);
    }

    /**
     * Writes a message to STDERR and optionally exit with a custom code.
     *
     * @param string $message The error message
     * @param int|null $exitCode Set null to do not exit
     */
    public static function error(string $message, ?int $exitCode = 1) : void
    {
        static::beep();
        \fwrite(\STDERR, static::style($message, static::FG_RED) . \PHP_EOL);
        if ($exitCode !== null) {
            exit($exitCode);
        }
    }

    /**
     * Clear the terminal screen.
     */
    public static function clear() : void
    {
        \fwrite(\STDOUT, "\e[H\e[2J");
    }

    /**
     * Get user input.
     *
     * NOTE: It is possible pass multiple lines ending each line with a backslash.
     *
     * @param string $prepend Text prepended in the input. Used internally to
     * allow multiple lines
     *
     * @return string Returns the user input
     */
    public static function getInput(string $prepend = '') : string
    {
        $input = \fgets(\STDIN);
        $input = $input === false ? '' : \trim($input);
        $prepend .= $input;
        $eolPos = false;
        if ($prepend) {
            $eolPos = \strrpos($prepend, '\\', -1);
        }
        if ($eolPos !== false) {
            $prepend = \substr_replace($prepend, \PHP_EOL, $eolPos);
            $prepend = static::getInput($prepend);
        }
        return $prepend;
    }

    /**
     * Prompt a question.
     *
     * @param string $question The question to prompt
     * @param array<int,string>|string|null $options Answer options. If an array
     * is set, the default answer is the first value. If is a string, it will
     * be the default.
     *
     * @return string The answer
     */
    public static function prompt(string $question, array | string $options = null) : string
    {
        if ($options !== null) {
            $options = \is_array($options)
                ? \array_values($options)
                : [$options];
        }
        if ($options) {
            $opt = $options;
            $opt[0] = static::style($opt[0], null, null, [static::FM_BOLD]);
            $optionsText = isset($opt[1])
                ? \implode(', ', $opt)
                : $opt[0];
            $question .= ' [' . $optionsText . ']';
        }
        $question .= ': ';
        \fwrite(\STDOUT, $question);
        $answer = static::getInput();
        if ($answer === '' && isset($options[0])) {
            $answer = $options[0];
        }
        return $answer;
    }

    /**
     * Creates a well formatted table.
     *
     * @param array<array<scalar|\Stringable>> $tbody Table body rows
     * @param array<scalar|\Stringable> $thead Table head fields
     */
    public static function table(array $tbody, array $thead = []) : void
    {
        // All the rows in the table will be here until the end
        $tableRows = [];
        // We need only indexes and not keys
        if ( ! empty($thead)) {
            $tableRows[] = \array_values($thead);
        }
        foreach ($tbody as $tr) {
            // cast tr to array if is not - (objects...)
            $tableRows[] = \array_values((array) $tr);
        }
        // Yes, it really is necessary to know this count
        $totalRows = \count($tableRows);
        // Store all columns lengths
        // $allColsLengths[row][column] = length
        $allColsLengths = [];
        // Store maximum lengths by column
        // $maxColsLengths[column] = length
        $maxColsLengths = [];
        // Read row by row and define the longest columns
        for ($row = 0; $row < $totalRows; $row++) {
            $column = 0; // Current column index
            foreach ($tableRows[$row] as $col) {
                // Sets the size of this column in the current row
                $allColsLengths[$row][$column] = static::strlen((string) $col);
                // If the current column does not have a value among the larger ones
                // or the value of this is greater than the existing one
                // then, now, this assumes the maximum length
                if ( ! isset($maxColsLengths[$column])
                    || $allColsLengths[$row][$column] > $maxColsLengths[$column]) {
                    $maxColsLengths[$column] = $allColsLengths[$row][$column];
                }
                // We can go check the size of the next column...
                $column++;
            }
        }
        // Read row by row and add spaces at the end of the columns
        // to match the exact column length
        for ($row = 0; $row < $totalRows; $row++) {
            $column = 0;
            foreach ($tableRows[$row] as $col => $value) {
                $diff = $maxColsLengths[$column] - $allColsLengths[$row][$col];
                if ($diff) {
                    $tableRows[$row][$column] .= \str_repeat(' ', $diff);
                }
                $column++;
            }
        }
        $table = $line = '';
        // Joins columns and append the well formatted rows to the table
        foreach ($tableRows as $row => $value) {
            // Set the table border-top
            if ($row === 0) {
                $line = '+';
                foreach (\array_keys($value) as $col) {
                    $line .= \str_repeat('-', $maxColsLengths[$col] + 2) . '+';
                }
                $table .= $line . \PHP_EOL;
            }
            // Set the vertical borders
            $table .= '| ' . \implode(' | ', $value) . ' |' . \PHP_EOL;
            // Set the thead and table borders-bottom
            if (($row === 0 && ! empty($thead)) || $row + 1 === $totalRows) {
                $table .= $line . \PHP_EOL;
            }
        }
        \fwrite(\STDOUT, $table);
    }
}
