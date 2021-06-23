<?php namespace Tests\CLI\Commands;

use Framework\CLI\Command;
use Framework\CLI\Console;
use PHPUnit\Framework\TestCase;

class NoPropertiesCommandTest extends TestCase
{
	protected Command $command;

	protected function setUp() : void
	{
		$this->command = new NoPropertiesCommand(new Console());
	}

	public function testAutoName() : void
	{
		self::assertSame('noproperties', $this->command->getName());
	}

	public function testAutoDescription() : void
	{
		self::assertSame(
			'This command does not provide a description.',
			$this->command->getDescription()
		);
	}
}
