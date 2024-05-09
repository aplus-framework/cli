<?php declare(strict_types=1);
/*
 * This file is part of Aplus Framework CLI Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Framework\CLI\Styles;

/**
 * Enum Format.
 *
 * @package cli
 */
enum Format : string
{
    case bold = 'bold';
    case faint = 'faint';
    case italic = 'italic';
    case underline = 'underline';
    case slowBlink = 'slow_blink';
    case rapidBlink = 'rapid_blink';
    case reverseVideo = 'reverse_video';
    case conceal = 'conceal';
    case crossedOut = 'crossed_out';
    case primaryFont = 'primary_font';
    case fraktur = 'fraktur';
    case doublyUnderline = 'doubly_underline';
    case encircled = 'encircled';

    public function getCode() : string
    {
        return match ($this->value) {
            'bold' => "\033[1m",
            'faint' => "\033[2m",
            'italic' => "\033[3m",
            'underline' => "\033[4m",
            'slow_blink' => "\033[5m",
            'rapid_blink' => "\033[6m",
            'reverse_video' => "\033[7m",
            'conceal' => "\033[8m",
            'crossed_out' => "\033[9m",
            'primary_font' => "\033[10m",
            'fraktur' => "\033[20m",
            'doubly_underline' => "\033[21m",
            'encircled' => "\033[52m",
        };
    }
}
