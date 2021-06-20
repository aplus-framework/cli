<?php namespace Tests\CLI;

use Framework\CLI\Stream;
use PHPUnit\Framework\TestCase;

final class StreamTest extends TestCase
{
	public function testStream() : void
	{
		Stream::init();
		self::assertSame('', Stream::getOutput());
		\fwrite(\STDOUT, 'foo');
		self::assertSame('foo', Stream::getOutput());
		\fwrite(\STDOUT, 'bar');
		self::assertSame('foobar', Stream::getOutput());
		Stream::reset();
		self::assertSame('', Stream::getOutput());
	}
}
