<?php namespace Tests\CLI\Commands;

use Framework\CLI\Console;
use Framework\CLI\Stream;
use PHPUnit\Framework\TestCase;

class HelpTest extends TestCase
{
	public function testHelp() : void
	{
		$console = new Console();
		$console->addCommand(Foo::class);
		Stream::init();
		$console->exec('help');
		$this->assertStringContainsString('Command', Stream::getOutput());
		$this->assertStringNotContainsString('Options', Stream::getOutput());
		Stream::reset();
		$console->exec('help foo');
		$this->assertStringContainsString('Options', Stream::getOutput());
	}
}
