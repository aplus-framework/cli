<?php namespace Framework\CLI;

/**
 * Class Command.
 */
abstract class Command
{
	/**
	 * Console instance of the current command.
	 *
	 * @var Console
	 */
	protected $console;
	/**
	 * Command name.
	 *
	 * @var string
	 */
	protected $name;
	/**
	 * Command description.
	 *
	 * @var string
	 */
	protected $description;
	/**
	 * Command usage.
	 *
	 * @var string
	 */
	protected $usage = 'command [options] -- [arguments]';
	/**
	 * Command options.
	 *
	 * @var array
	 */
	protected $options = [];
	/**
	 * Tells if command is active.
	 *
	 * @var bool
	 */
	protected $active = true;

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
	 *
	 * @param array $options   Command line options
	 * @param array $arguments Command line arguments
	 */
	abstract public function run(array $options = [], array $arguments = []) : void;

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
	 * @return array
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
