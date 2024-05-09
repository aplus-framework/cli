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
 * Enum ForegroundColor.
 *
 * @package cli
 */
enum ForegroundColor : string
{
    case black = 'black';
    case red = 'red';
    case green = 'green';
    case yellow = 'yellow';
    case blue = 'blue';
    case magenta = 'magenta';
    case cyan = 'cyan';
    case white = 'white';
    case brightBlack = 'bright_black';
    case brightRed = 'bright_red';
    case brightGreen = 'bright_green';
    case brightYellow = 'bright_yellow';
    case brightBlue = 'bright_blue';
    case brightMagenta = 'bright_magenta';
    case brightCyan = 'bright_cyan';
    case brightWhite = 'bright_white';

    public function getCode() : string
    {
        return match ($this->value) {
            'black' => "\033[0;30m",
            'red' => "\033[0;31m",
            'green' => "\033[0;32m",
            'yellow' => "\033[0;33m",
            'blue' => "\033[0;34m",
            'magenta' => "\033[0;35m",
            'cyan' => "\033[0;36m",
            'white' => "\033[0;37m",
            'bright_black' => "\033[0;90m",
            'bright_red' => "\033[0;91m",
            'bright_green' => "\033[0;92m",
            'bright_yellow' => "\033[0;93m",
            'bright_blue' => "\033[0;94m",
            'bright_magenta' => "\033[0;95m",
            'bright_cyan' => "\033[0;96m",
            'bright_white' => "\033[0;97m",
        };
    }
}
