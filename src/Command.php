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

	public function __construct(Console $console)
	{
		$this->console = $console;
	}

	public function __get($property)
	{
		return $this->{$property};
	}

	abstract public function run(array $options = [], array $arguments = []) : void;
}
