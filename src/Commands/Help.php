<?php namespace Framework\CLI\Commands;

use Framework\CLI\CLI;
use Framework\CLI\Command;

class Help extends Command
{
	protected string $name = 'help';
	protected string $usage = 'help [command_name]';

	public function run() : void
	{
		$command = $this->console->getArgument(0) ?? 'help';
		$this->showCommand($command);
	}

	protected function showCommand(string $command_name)
	{
		$command = $this->console->getCommand($command_name);
		if ($command === null) {
			CLI::error(CLI::style(
				$this->console->getLanguage()->render('cli', 'commandNotFound', [$command_name]),
				CLI::FG_BRIGHT_RED
			));
		}
		CLI::write(CLI::style(
			$this->console->getLanguage()->render('cli', 'command') . ': ',
			'green'
		) . $command->getName());
		if ($value = $command->getDescription()) {
			CLI::write(CLI::style(
				$this->console->getLanguage()->render('cli', 'description') . ': ',
				'green'
			) . $value);
		}
		if ($value = $command->getUsage()) {
			CLI::write(CLI::style(
				$this->console->getLanguage()->render('cli', 'usage') . ': ',
				'green'
			) . $value);
		}
		if ($value = $command->getOptions()) {
			CLI::write($this->console->getLanguage()->render('cli', 'options') . ': ', 'green');
			$last_key = \array_key_last($value);
			foreach ($value as $option => $description) {
				CLI::write('  ' . $this->renderOption($option));
				CLI::write('  ' . $description);
				if ($option !== $last_key) {
					CLI::newLine();
				}
			}
		}
	}

	protected function renderOption(string $text) : string
	{
		$text = \trim(\preg_replace('/\s+/', '', $text));
		$text = \explode(',', $text);
		\sort($text);
		foreach ($text as &$item) {
			$item = CLI::style($item, CLI::FG_YELLOW);
		}
		return \implode(', ', $text);
	}

	public function getDescription() : string
	{
		return $this->console->getLanguage()->render('cli', 'help.description');
	}
}
