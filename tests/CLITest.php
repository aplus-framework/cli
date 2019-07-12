<?php namespace Tests\CLI;

use Framework\CLI\CLI;
use PHPUnit\Framework\TestCase;

class CLITest extends TestCase
{
	protected function setUp() : void
	{
		Stream::init();
	}

	protected function tearDown() : void
	{
		Stream::reset();
	}

	public function testWrite()
	{
		CLI::write('Hello!');
		$this->assertEquals(Stream::$output, "Hello!\n");
	}
}
