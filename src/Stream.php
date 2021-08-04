<?php declare(strict_types=1);
/*
 * This file is part of Aplus Framework CLI Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Framework\CLI;

use JetBrains\PhpStorm\Deprecated;
use JetBrains\PhpStorm\Pure;

/**
 * Class Stream.
 *
 * @codeCoverageIgnore
 *
 * @deprecated Use Framework\CLI\Streams classes
 */
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

    #[Deprecated(
        'Since CLI Library version 1.16, use Stdout instead',
        '\Framework\CLI\Streams\Stdout::init()'
    )]
    public static function init() : void
    {
        \trigger_error(
            'The class ' . __CLASS__ . ' is deprecated',
            \E_USER_DEPRECATED
        );
        \stream_filter_register(static::class, static::class);
        \stream_filter_append(\STDOUT, static::class);
    }

    #[Pure]
    public static function getOutput() : string
    {
        return static::$output;
    }

    public static function reset() : void
    {
        static::$output = '';
    }
}
