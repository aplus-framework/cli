<?php namespace Tests\CLI;

use Framework\CLI\Console;

class ConsoleMock extends Console
{
	public string $command;
	public array $options = [];
	public array $arguments = [];

	public function prepare()
	{
		parent::prepare();
	}
}
