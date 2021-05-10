<?php namespace Tests\CLI;

use Framework\CLI\Stream;
use PHPUnit\Framework\TestCase;

class StreamTest extends TestCase
{
	public function testStream()
	{
		Stream::init();
		$this->assertEquals('', Stream::getOutput());
		\fwrite(\STDOUT, 'foo');
		$this->assertEquals('foo', Stream::getOutput());
		\fwrite(\STDOUT, 'bar');
		$this->assertEquals('foobar', Stream::getOutput());
		Stream::reset();
		$this->assertEquals('', Stream::getOutput());
	}
}
