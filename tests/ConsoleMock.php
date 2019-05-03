<?php namespace Tests\CLI;

use Framework\CLI\Console;

class ConsoleMock extends Console
{
	public $command;
	public $options = [];
	public $arguments = [];

	public function prepare()
	{
		parent::prepare();
	}
}
