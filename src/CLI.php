<?php namespace Framework\CLI;

class CLI
{
	public const BG_BLACK = 'black';
	public const BG_RED = 'red';
	public const BG_GREEN = 'green';
	public const BG_YELLOW = 'yellow';
	public const BG_BLUE = 'blue';
	public const BG_MAGENTA = 'magenta';
	public const BG_CYAN = 'cyan';
	public const BG_WHITE = 'white';
	public const BG_BRIGHT_BLACK = 'bright_black';
	public const BG_BRIGHT_RED = 'bright_red';
	public const BG_BRIGHT_GREEN = 'bright_green';
	public const BG_BRIGHT_YELLOW = 'bright_yellow';
	public const BG_BRIGHT_BLUE = 'bright_blue';
	public const BG_BRIGHT_MAGENTA = 'bright_magenta';
	public const BG_BRIGHT_CYAN = 'bright_cyan';
	public const FG_BLACK = 'black';
	public const FG_RED = 'red';
	public const FG_GREEN = 'green';
	public const FG_YELLOW = 'yellow';
	public const FG_BLUE = 'blue';
	public const FG_MAGENTA = 'magenta';
	public const FG_CYAN = 'cyan';
	public const FG_WHITE = 'white';
	public const FG_BRIGHT_BLACK = 'bright_black';
	public const FG_BRIGHT_RED = 'bright_red';
	public const FG_BRIGHT_GREEN = 'bright_green';
	public const FG_BRIGHT_YELLOW = 'bright_yellow';
	public const FG_BRIGHT_BLUE = 'bright_blue';
	public const FG_BRIGHT_MAGENTA = 'bright_magenta';
	public const FG_BRIGHT_CYAN = 'bright_cyan';
	public const FG_BRIGHT_WHITE = 'bright_white';
	protected static $background_colors = [
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
	protected static $foreground_colors = [
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
	protected static $formats = [
		'bold' => "\033[1m",
		'faint' => "\033[2m",
		'italic' => "\033[3m",
		'underline' => "\033[4m",
		'slow_blink' => "\033[5m",
		'rapid_blink' => "\033[6m",
		'reverse_video' => "\033[7m",
		'Conceal' => "\033[8m",
		'crossed_out' => "\033[9m",
		'primary_font' => "\033[10m",
		'fraktur' => "\033[20m",
		'doubly_underline' => "\033[21m",
		'encircled' => "\033[52m",
	];
	protected static $reset = "\033[0m";

	public static function isWindows() : bool
	{
		return \PHP_OS_FAMILY === 'Windows';
	}

	public static function getWidth(int $default = 80) : int
	{
		if (static::isWindows() || ! $width = (int) \shell_exec('tput cols')) {
			return $default;
		}
		return $width;
	}

	public static function wrap(string $text, int $width = null) : string
	{
		$width = $width ?? static::getWidth();
		$text = \wordwrap($text, $width, \PHP_EOL, true);
		return $text;
	}

	public static function strlen(string $text) : int
	{
		$codes = [];
		foreach (static::$foreground_colors as $color) {
			$codes[] = $color;
		}
		foreach (static::$background_colors as $background) {
			$codes[] = $background;
		}
		foreach (static::$formats as $format) {
			$codes[] = $format;
		}
		$codes[] = static::$reset;
		$text = \str_replace($codes, '', $text);
		return \mb_strlen($text);
	}

	public static function style(
		string $text,
		string $color,
		string $background = null,
		array $formats = []
	) : string {
		$string = '';
		if ($color) {
			if (empty(static::$foreground_colors[$color])) {
				throw new \Exception('Invalid color: ' . $color);
			}
			$string = static::$foreground_colors[$color];
		}
		if ($background) {
			if (empty(static::$background_colors[$background])) {
				throw new \Exception('Invalid background color: ' . $background);
			}
			$string .= static::$background_colors[$background];
		}
		if ($formats) {
			foreach ($formats as $format) {
				if (empty(static::$formats[$format])) {
					throw new \Exception('Invalid format: ' . $format);
				}
				$string .= static::$formats[$format];
			}
		}
		$string .= $text . static::$reset;
		return $string;
	}

	public static function write(
		string $text,
		string $color = null,
		string $background = null,
		int $width = null
	) : void {
		if ($color || $background) {
			$text = static::style($text, $color, $background);
		}
		if ($width) {
			$text = static::wrap($text, $width);
		}
		\fwrite(\STDOUT, $text . \PHP_EOL);
	}

	public static function newLine(int $lines = 1) : void
	{
		for ($i = 0; $i < $lines; $i++) {
			\fwrite(\STDOUT, \PHP_EOL);
		}
	}

	public static function beep(int $times = 1, int $sleep = 1) : void
	{
		for ($i = 0; $i < $times; $i++) {
			\fwrite(\STDOUT, "\x07");
			//\sleep($sleep);
		}
	}

	/**
	 * Writes a message box.
	 *
	 * @param array|string $lines      one line as string or multi-lines as array
	 * @param string       $background background color
	 * @param int          $width
	 */
	public static function box($lines, string $background = 'blue', int $width = null) //: void
	{
		$width = $width ?? static::getWidth();
		$width -= 2;
		if ( ! \is_array($lines)) {
			$lines = [
				$lines,
			];
		}
		$all_lines = [];
		foreach ($lines as $key => &$line) {
			$length = static::strlen($line);
			if ($length > $width) {
				$line = static::wrap($line, $width);
			}
			foreach (\explode(\PHP_EOL, $line) as $sub_line) {
				$all_lines[] = $sub_line;
			}
		}
		unset($line);
		$color = static::FG_BRIGHT_WHITE;
		if ($background === $color) {
			$color = static::FG_BLACK;
		}
		$blank_line = \str_repeat(' ', $width + 2);
		$text = static::style($blank_line, $color, $background);
		//static::write($blank_line, $color, $background);
		foreach ($all_lines as $key => $line) {
			$end = \str_repeat(' ', $width - static::strlen($line)) . ' ';
			$end = static::style($end, $color, $background);
			//static::write(' ' . $line . $end, $color, $background);
			$text .= static::style(' ' . $line . $end, $color, $background);
		}
		$text .= static::style($blank_line, $color, $background);
		//static::write($blank_line, $color, $background);
		//static::write($blank_line, $color, $background);
		return $text;
	}

	public static function error(string $content) : void
	{
		static::beep();
		\fwrite(\STDERR, $content . \PHP_EOL);
		exit(1);
	}

	public static function clear() : void
	{
		\fwrite(\STDOUT, "\e[H\e[2J");
	}

	public static function getInput(string $prepend = '') : string
	{
		$input = \fgets(\STDIN);
		$input = $input ? \trim($input) : '';
		$prepend .= $input;
		$eol_pos = \strrpos($prepend, '\\', -1);
		if ($eol_pos !== false) {
			$prepend = \substr_replace($prepend, \PHP_EOL, $eol_pos);
			$prepend = static::getInput($prepend);
		}
		return $prepend;
	}

	/**
	 * Prompt a question.
	 *
	 * @param string            $question the question to prompt
	 * @param array|string|null $options  Answer options. If an array is set, the default answer is
	 *                                    the first value. If is an string, it will be the default.
	 *
	 * @return string the answer
	 */
	public static function prompt(string $question, $options = null) : string
	{
		if ($options !== null) {
			$options = \is_array($options)
				? \array_values($options)
				: [$options];
			$options_text = isset($options[1])
				? \implode(', ', $options)
				: $options[0];
			$question .= ' [' . $options_text . ']';
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
	 * @param array $tbody table body rows
	 * @param array $thead table head fields
	 */
	public static function table(array $tbody, array $thead = []) : void
	{
		// All the rows in the table will be here until the end
		$table_rows = [];
		// We need only indexes and not keys
		if ( ! empty($thead)) {
			$table_rows[] = \array_values($thead);
		}
		foreach ($tbody as $tr) {
			// cast tr to array if is not - (objects...)
			$table_rows[] = \array_values((array) $tr);
		}
		// Yes, it really is necessary to know this count
		$total_rows = \count($table_rows);
		// Store all columns lengths
		// $all_cols_lengths[row][column] = length
		$all_cols_lengths = [];
		// Store maximum lengths by column
		// $max_cols_lengths[column] = length
		$max_cols_lengths = [];
		// Read row by row and define the longest columns
		for ($row = 0; $row < $total_rows; $row++) {
			$column = 0; // Current column index
			foreach ($table_rows[$row] as $col) {
				// Sets the size of this column in the current row
				$all_cols_lengths[$row][$column] = static::strlen($col);
				// If the current column does not have a value among the larger ones
				// or the value of this is greater than the existing one
				// then, now, this assumes the maximum length
				if ( ! isset($max_cols_lengths[$column]) || $all_cols_lengths[$row][$column] > $max_cols_lengths[$column]) {
					$max_cols_lengths[$column] = $all_cols_lengths[$row][$column];
				}
				// We can go check the size of the next column...
				$column++;
			}
		}
		// Read row by row and add spaces at the end of the columns
		// to match the exact column length
		for ($row = 0; $row < $total_rows; $row++) {
			$column = 0;
			foreach ($table_rows[$row] as $col => $value) {
				$diff = $max_cols_lengths[$column] - $all_cols_lengths[$row][$col];
				if ($diff) {
					$table_rows[$row][$column] .= \str_repeat(' ', $diff);
				}
				$column++;
			}
		}
		$table = '';
		// Joins columns and append the well formatted rows to the table
		for ($row = 0; $row < $total_rows; $row++) {
			// Set the table border-top
			if ($row === 0) {
				$line = '+';
				foreach ($table_rows[$row] as $col => $value) {
					$line .= \str_repeat('-', $max_cols_lengths[$col] + 2) . '+';
				}
				$table .= $line . \PHP_EOL;
			}
			// Set the vertical borders
			$table .= '| ' . \implode(' | ', $table_rows[$row]) . ' |' . \PHP_EOL;
			// Set the thead and table borders-bottom
			if (($row === 0 && ! empty($thead)) || $row + 1 === $total_rows) {
				$table .= $line . \PHP_EOL;
			}
		}
		\fwrite(\STDOUT, $table);
	}
}
