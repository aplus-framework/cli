<?php namespace Tests\CLI;

use PHPUnit\Framework\TestCase;

class LanguagesTest extends TestCase
{
	protected string $langDir = __DIR__ . '/../src/Languages/';

	protected function getCodes()
	{
		$codes = \array_filter(\glob($this->langDir . '*'), 'is_dir');
		$length = \strlen($this->langDir);
		foreach ($codes as &$dir) {
			$dir = \substr($dir, $length);
		}
		return $codes;
	}

	public function testKeys() : void
	{
		$rules = require $this->langDir . 'en/cli.php';
		$rules = \array_keys($rules);
		foreach ($this->getCodes() as $code) {
			$lines = require $this->langDir . $code . '/cli.php';
			$lines = \array_keys($lines);
			\sort($lines);
			$this->assertEquals($rules, $lines, 'Language: ' . $code);
		}
	}
}
