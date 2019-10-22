<?php namespace Tests\CLI;

use Framework\CLI\CLI;
use Framework\CLI\Command;

class CommandMock extends Command
{
	protected $name = 'test';
	protected $description = 'Lorem ipsum';
	protected $usage = 'test';
	protected $options = [
		'-b' => 'foo bar',
	];

	public function run(array $options = [], array $arguments = []) : void
	{
		CLI::write(\print_r($options, true));
		CLI::write(\print_r($arguments, true));
	}
}
