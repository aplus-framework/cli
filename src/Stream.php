<?php namespace Framework\CLI;

class Stream extends \php_user_filter
{
	protected static string $output = '';

	/**
	 * @param resource $in
	 * @param resource $out
	 * @param int $consumed
	 * @param bool $closing
	 *
	 * @see https://php.net/manual/en/php-user-filter.filter.php
	 *
	 * @return int
	 */
	public function filter($in, $out, &$consumed, $closing)
	{
		while ($bucket = \stream_bucket_make_writeable($in)) {
			static::$output .= $bucket->data; // @phpstan-ignore-line
			$consumed += $bucket->datalen; // @phpstan-ignore-line
			\stream_bucket_append($out, $bucket);
		}
		return \PSFS_FEED_ME;
	}

	public static function init() : void
	{
		\stream_filter_register('stream', static::class);
		\stream_filter_append(\STDOUT, 'stream');
	}

	public static function getOutput() : string
	{
		return static::$output;
	}

	public static function reset() : void
	{
		static::$output = '';
	}
}
