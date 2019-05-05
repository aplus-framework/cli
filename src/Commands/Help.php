<?php namespace Framework\CLI\Commands;

use Framework\CLI\CLI;
use Framework\CLI\Command;

class Help extends Command
{
	protected $name = 'help';
	protected $usage = 'help [command_name]';

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
