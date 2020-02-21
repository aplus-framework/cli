<?php namespace Tests\CLI;

use Framework\CLI\Console;
use PHPUnit\Framework\TestCase;

class CommandTest extends TestCase
{
	protected CommandMock $command;

	protected function setUp() : void
	{
		$this->command = new CommandMock(new Console());
	}

	public function testDescription()
	{
		$this->assertEquals('Lorem ipsum', $this->command->getDescription());
	}

	public function testUsage()
	{
		$this->assertEquals('test', $this->command->getUsage());
	}

	public function testOptions()
	{
		$this->assertEquals(['-b' => 'foo bar'], $this->command->getOptions());
	}
}
