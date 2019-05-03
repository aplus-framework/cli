<?php namespace Framework\CLI;

abstract class Command
{
	protected $name;
	protected $options = [];

	public function __get($property)
	{
		return $this->{$property};
	}

	abstract public function run(array $options = [], array $arguments = []) : void;
}
