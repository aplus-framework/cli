<?php namespace Framework\CLI;

use Framework\CLI\Commands\Help;
use Framework\CLI\Commands\Index;

class Console
{
	/**
	 * @var array|Command[]
	 */
	protected $commands = [];
	protected $command = '';
	protected $options = [];
	protected $arguments = [];

	public function __construct()
	{
		$this->prepare();
	}

	public function addCommand(Command $command)
	{
		$this->commands[$command->name] = $command;
		return $this;
	}

	public function getCommand(string $name) : Command
	{
		if (isset($this->commands[$name])) {
			return $this->commands[$name];
		}
		CLI::error("Command not found: {$name}");
	}

	public function getCommands() : array
	{
		return $this->commands;
	}

	public function run() : void
	{
		$this->addCommand(new Index($this));
		$this->addCommand(new Help($this));
		if ($this->command === '') {
			$this->command = 'index';
		}
		$command = $this->getCommand($this->command);
		$command->run($this->options, $this->arguments);
	}

	/**
	 * [options] [arguments] [options]
	 * [options] -- [arguments]
	 * [command]
	 * [command] [options] [arguments] [options]
	 * [command] [options] -- [arguments]
	 * Short option: -l, -la === l = true, a = true
	 * Long option: --list, --all=vertical === list = true, all = vertical
	 * Only Long Options receive values:
	 * --foo=bar or --f=bar - "foo" and "f" are bar
	 * -foo=bar or -f=bar - all characters are true (f, o, =, b, a, r)
	 * After -- all values are arguments, also if is prefixed with -
	 * Without --, arguments and options can be mixed: -ls foo -x abc --a=e.
	 */
	protected function prepare()
	{
		global $argv;
		$this->command = '';
		$this->options = [];
		$this->arguments = [];
		$argument_values = $argv;
		unset($argument_values[0]);
		if (isset($argument_values[1]) && $argument_values[1][0] !== '-') {
			$this->command = $argument_values[1];
			unset($argument_values[1]);
		}
		$end_options = false;
		foreach ($argument_values as $value) {
			if ($end_options === false && $value === '--') {
				$end_options = true;
				continue;
			}
			if ($end_options === false && $value[0] === '-') {
				if (isset($value[1]) && $value[1] === '-') {
					$option = \substr($value, 2);
					if (\strpos($option, '=') !== false) {
						[$option, $value] = \explode('=', $option, 2);
						$this->options[$option] = $value;
						continue;
					}
					$this->options[$option] = true;
					continue;
				}
				foreach (\str_split(\substr($value, 1)) as $item) {
					$this->options[$item] = true;
				}
				continue;
			}
			//$end_options = true;
			$this->arguments[] = $value;
		}
	}
}
