<?php namespace Framework\CLI;

class CLI
{
	public static function write(string $text) : void
	{
		\fwrite(\STDOUT, $text . \PHP_EOL);
	}
}
