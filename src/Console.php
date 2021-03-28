<?php namespace Framework\CLI;

use Framework\CLI\Commands\Help;
use Framework\CLI\Commands\Index;
use Framework\Language\Language;

/**
 * Class Console.
 */
class Console
{
	/**
	 * List of commands.
	 *
	 * @var array|Command[]
	 */
	protected array $commands = [];
	/**
	 * The current command name.
	 */
	protected string $command = '';
	/**
	 * Input options.
	 *
	 * @var array|mixed[]
	 */
	protected array $options = [];
	/**
	 * Input arguments.
	 *
	 * @var array|string[]
	 */
	protected array $arguments = [];
	/**
	 * The Language instance.
	 */
	protected Language $language;

	/**
	 * Console constructor.
	 *
	 * @param Language|null $language
	 */
	public function __construct(Language $language = null)
	{
		if ($language === null) {
			$language = new Language('en');
		}
		$this->language = $language->addDirectory(__DIR__ . '/Languages');
		$this->prepare();
	}

	/**
	 * Get all CLI options.
	 *
	 * @return array|string[]
	 */
	public function getOptions() : array
	{
		return $this->options;
	}

	/**
	 * Get a specific option or null.
	 *
	 * @param string $option
	 *
	 * @return string|null
	 */
	public function getOption(string $option) : ?string
	{
		return $this->options[$option] ?? null;
	}

	/**
	 * Get all arguments.
	 *
	 * @return array|string[]
	 */
	public function getArguments() : array
	{
		return $this->arguments;
	}

	/**
	 * Get a specific argument or null.
	 *
	 * @param string $argument
	 *
	 * @return string|null
	 */
	public function getArgument(string $argument) : ?string
	{
		return $this->arguments[$argument] ?? null;
	}

	/**
	 * Get the Language instance.
	 *
	 * @return Language
	 */
	public function getLanguage() : Language
	{
		return $this->language;
	}

	/**
	 * Add a command to the console.
	 *
	 * @param Command $command
	 *
	 * @return $this
	 */
	public function addCommand(Command $command)
	{
		$this->commands[$command->getName()] = $command;
		return $this;
	}

	/**
	 * Get a command.
	 *
	 * @param string $name Command name
	 *
	 * @return Command|null The command on success or null if not found
	 */
	public function getCommand(string $name) : ?Command
	{
		if (isset($this->commands[$name]) && $this->commands[$name]->isActive()) {
			return $this->commands[$name];
		}
		return null;
	}

	/**
	 * Get a list of active commands.
	 *
	 * @return array|Command[]
	 */
	public function getCommands() : array
	{
		$commands = $this->commands;
		foreach ($commands as $name => $command) {
			if ( ! $command->isActive()) {
				unset($commands[$name]);
			}
		}
		\ksort($commands);
		return $commands;
	}

	/**
	 * Run the Console.
	 */
	public function run() : void
	{
		if ($this->getCommand('index') === null) {
			$this->addCommand(new Index($this));
		}
		if ($this->getCommand('help') === null) {
			$this->addCommand(new Help($this));
		}
		if ($this->command === '') {
			$this->command = 'index';
		}
		$command = $this->getCommand($this->command);
		if ($command === null) {
			CLI::error(CLI::style(
				$this->getLanguage()->render('cli', 'commandNotFound', [$this->command]),
				CLI::FG_BRIGHT_RED
			));
		}
		//$op = $this->filterCommandOptions($command);
		$command->run();
	}

	/*protected function filterCommandOptions(Command $command) : array
	{
		$options = $command->getOptions();
		$options = \array_keys($options);
		foreach ($options as &$option) {
			$option = \trim(\preg_replace('/\s+/', '', $option));
			$option = \explode(',', $option);
			$option = \array_map(static function ($item) {
				return \ltrim($item, '-');
			}, $option);
			\sort($option);
		}
		unset($option);
		$result = [];
		foreach ($options as $option) {
			$key = null;
			foreach ($option as $item) {
				$result[$item] = null;
				//$result[$item] =& $this->options[$key];
				if (\array_key_exists($item, $this->options)) {
					//$value = $this->options[$item];
					$key = $item;
					break;
				}
			}
			//\var_dump($key);
			if ($key) {
				foreach ($option as $item) {
					$result[$item] = &$this->options[$key];
				}
				break;
			}
		}
		return $result;
	}*/

	/**
	 * Prepare informations of the command line.
	 *
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
	protected function prepare() : void
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
					if (\str_contains($option, '=')) {
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
