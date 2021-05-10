<?php namespace Tests\CLI;

use Framework\CLI\Console;

class ConsoleMock extends Console
{
	public string $command;

	public function prepare(array $argument_values) : void
	{
		parent::prepare($argument_values);
	}
}
