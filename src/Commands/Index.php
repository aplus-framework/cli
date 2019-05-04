<?php namespace Framework\CLI\Commands;

use Framework\CLI\CLI;
use Framework\CLI\Command;

class Index extends Command
{
	protected $name = 'index';
	protected $description = 'Show commands list';
	protected $usage = 'index';

	public function run(array $options = [], array $arguments = []) : void
	{
		$this->showHeader();
		$this->showDate();
		$this->listCommands();
	}

	public function listCommands()
	{
		$width = 0;
		$lengths = [];
		foreach (\array_keys($this->console->getCommands()) as $name) {
			$lengths[$name] = \mb_strlen($name);
			if ($lengths[$name] > $width) {
				$width = $lengths[$name];
			}
		}
		CLI::write($this->console->getLanguage()->render('cli', 'commands') . ':', 'yellow');
		foreach ($this->console->getCommands() as $name => $command) {
			CLI::write(
				'  ' . CLI::style($name, 'green') . '  '
				. \str_repeat(' ', $width - $lengths[$name])
				. $command->description
			);
		}
	}

	protected function showHeader()
	{
		$text = <<<EOL
 _____                                            _
|  ___| __ __ _ _ __ ___   _____      _____  _ __| | __
| |_ | '__/ _` | '_ ` _ \\ / _ \\ \\ /\\ / / _ \\| '__| |/ /
|  _|| | | (_| | | | | | |  __/\\ V  V / (_) | |  |   <
|_|  |_|  \\__,_|_| |_| |_|\\___| \\_/\\_/ \\___/|_|  |_|\\_\\

EOL;
		CLI::write($text, 'green');
	}

	protected function showDate()
	{
		$text = $this->console->getLanguage()->date(\time(), 'full');
		$text = \ucfirst($text) . ' - '
			. \date('H:i:s') . ' - '
			. \date_default_timezone_get() . \PHP_EOL;
		CLI::write($text);
	}
}
