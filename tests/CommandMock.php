<?php namespace Tests\CLI;

use Framework\CLI\CLI;
use Framework\CLI\Command;

class CommandMock extends Command
{
	protected string $name = 'test';
	protected string $description = 'Lorem ipsum';
	protected string $usage = 'test';
	protected array $options = [
		'-b' => 'foo bar',
	];

	public function run() : void
	{
		CLI::write(\print_r($this->console->getOptions(), true));
		CLI::write(\print_r($this->console->getOption('o'), true));
		CLI::write(\print_r($this->console->getArguments(), true));
		CLI::write(\print_r($this->console->getArgument(1), true));
	}
}
