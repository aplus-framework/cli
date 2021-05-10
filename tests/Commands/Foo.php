<?php namespace Tests\CLI\Commands;

use Framework\CLI\CLI;
use Framework\CLI\Command;

class Foo extends Command
{
	protected string $name = 'foo';
	protected string $description = 'Foo command test';
	protected array $options = [
		'-o,--opt' => 'Set option as true',
		'--option' => 'Set option with param',
	];

	public function run() : void
	{
		CLI::write(__CLASS__);
	}
}
