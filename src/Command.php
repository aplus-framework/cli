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
		return $this->name;
	}

	/**
	 * Get command description.
	 *
	 * @return string
	 */
	public function getDescription() : string
	{
		return $this->description;
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
