<?php namespace Framework\CLI\Commands;

use Framework\CLI\CLI;
use Framework\CLI\Command;
use Framework\CLI\Console;

class Help extends Command
{
	/**
	 * @var Console
	 */
	protected $console;
	protected $name = 'help';
	protected $description = 'Show command usage help';
	protected $usage = 'help [command_name]';

	public function __construct(Console $console)
	{
		$this->console = $console;
	}

	public function run(array $options = [], array $arguments = []) : void
	{
		if (empty($arguments)) {
			$arguments[0] = 'help';
		}
		$this->showCommand($arguments[0]);
	}

	protected function showCommand(string $command)
	{
		$command = $this->console->getCommand($command);
		CLI::write(CLI::style('Command: ', 'green') . $command->name);
		if ($command->usage) {
			CLI::write(CLI::style('Usage: ', 'green') . $command->usage);
		}
		if ($command->description) {
			CLI::write(CLI::style('Description: ', 'green') . $command->description);
		}
		if ($command->options) {
			CLI::write('Options: ', 'green');
			foreach ($command->options as $option => $description) {
				CLI::write("- {$option}: {$description}");
			}
		}
	}
}
