<?php namespace Framework\CLI;

abstract class Command
{
	/**
	 * @var Console
	 */
	protected $console;
	protected $name;
	protected $description;
	protected $usage = 'command [options] -- [arguments]';
	protected $options = [];
	protected $active = true;

	public function __construct(Console $console)
	{
		$this->console = $console;
	}

	abstract public function run(array $options = [], array $arguments = []) : void;

	public function getName() : string
	{
		return $this->name;
	}

	public function getDescription() : string
	{
		return $this->description;
	}

	public function getUsage() : string
	{
		return $this->usage;
	}

	public function getOptions() : array
	{
		return $this->options;
	}

	public function isActive() : bool
	{
		return $this->active;
	}
}
