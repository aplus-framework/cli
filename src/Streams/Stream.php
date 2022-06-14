<?php declare(strict_types=1);
/*
 * This file is part of Aplus Framework CLI Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Framework\CLI\Streams;

use JetBrains\PhpStorm\Pure;

/**
 * Class Stream.
 *
 * @package cli
 */
abstract class Stream extends \php_user_filter
{
    protected static string $contents = '';

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
    public function filter($in, $out, &$consumed, $closing) : int
    {
        while ($bucket = \stream_bucket_make_writeable($in)) {
            static::$contents .= $bucket->data;
            $consumed += $bucket->datalen;
            \stream_bucket_append($out, $bucket);
        }
        return \PSFS_FEED_ME;
    }

    abstract public static function init() : void;

    #[Pure]
    public static function getContents() : string
    {
        return static::$contents;
    }

    public static function reset() : void
    {
        static::$contents = '';
    }
}
