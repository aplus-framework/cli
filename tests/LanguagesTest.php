<?php namespace Tests\CLI;

use PHPUnit\Framework\TestCase;

final class LanguagesTest extends TestCase
{
	protected string $langDir = __DIR__ . '/../src/Languages/';

	/**
	 * @return array<int,string>
	 */
	protected function getCodes() : array
	{
		$codes = \array_filter((array) \glob($this->langDir . '*'), 'is_dir');
		$length = \strlen($this->langDir);
		$result = [];
		foreach ($codes as $dir) {
			if ($dir === false) {
				continue;
			}
			$result[] = \substr($dir, $length);
		}
		return $result;
	}

	public function testKeys() : void
	{
		$rules = require $this->langDir . 'en/cli.php';
		$rules = \array_keys($rules);
		foreach ($this->getCodes() as $code) {
			$lines = require $this->langDir . $code . '/cli.php';
			$lines = \array_keys($lines);
			\sort($lines);
			self::assertSame($rules, $lines, 'Language: ' . $code);
		}
	}
}
