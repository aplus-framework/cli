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
 * Enum BackgroundColor.
 *
 * @package cli
 */
enum BackgroundColor : string
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
            'black' => "\033[40m",
            'red' => "\033[41m",
            'green' => "\033[42m",
            'yellow' => "\033[43m",
            'blue' => "\033[44m",
            'magenta' => "\033[45m",
            'cyan' => "\033[46m",
            'white' => "\033[47m",
            'bright_black' => "\033[100m",
            'bright_red' => "\033[101m",
            'bright_green' => "\033[102m",
            'bright_yellow' => "\033[103m",
            'bright_blue' => "\033[104m",
            'bright_magenta' => "\033[105m",
            'bright_cyan' => "\033[106m",
            'bright_white' => "\033[107m",
        };
    }
}
