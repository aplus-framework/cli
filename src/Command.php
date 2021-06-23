<?php namespace Framework\CLI;

/**
 * Class Command.
 */
abstract class Command
{
	/**
	 * Console instance of the current command.
	 */
	protected Console $console;
	/**
	 * Command name.
	 */
	protected string $name;
	/**
	 * Command description.
	 */
	protected string $description;
	/**
	 * Command usage.
	 */
	protected string $usage = 'command [options] -- [arguments]';
	/**
	 * Command options.
	 *
	 * @var array<string,string>
	 */
	protected array $options = [];
	/**
	 * Tells if command is active.
	 */
	protected bool $active = true;

	/**
	 * Command constructor.
	 *
	 * @param Console $console
	 */
	public function __construct(Console $console)
	{
		$this->console = $console;
	}

	/**
	 * Run the command.
	 */
	abstract public function run() : void;

	/**
	 * Get command name.
	 *
	 * @return string
	 */
	public function getName() : string
	{
		if (isset($this->name)) {
			return $this->name;
		}
		$name = static::class;
		$pos = \strrpos($name, '\\');
		if ($pos !== false) {
			$name = \substr($name, $pos + 1);
		}
		if (\str_ends_with($name, 'Command')) {
			$name = \substr($name, 0, -7);
		}
		$name = \strtolower($name);
		return $this->name = $name;
	}

	/**
	 * Get command description.
	 *
	 * @return string
	 */
	public function getDescription() : string
	{
		if (isset($this->description)) {
			return $this->description;
		}
		$description = 'This command does not provide a description.';
		return $this->description = $description;
	}

	/**
	 * Get command usage.
	 *
	 * @return string
	 */
	public function getUsage() : string
	{
		return $this->usage;
	}

	/**
	 * Get command options.
	 *
	 * @return array<string,string>
	 */
	public function getOptions() : array
	{
		return $this->options;
	}

	/**
	 * Tells if the command is active.
	 *
	 * @return bool
	 */
	public function isActive() : bool
	{
		return $this->active;
	}
}
