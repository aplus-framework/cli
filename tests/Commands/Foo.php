<?php
/*
 * This file is part of Aplus Framework CLI Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\Cli\Commands;

use Aplus\Cli\Cli;
use Aplus\Cli\Command;

class Foo extends Command
{
    protected string $name = 'foo';
    protected string $group = 'Foo';
    protected string $description = 'Foo command test';
    protected array $options = [
        '-o,--opt' => 'Set option as true',
        '--option' => 'Set option with param',
    ];

    public function run() : void
    {
        Cli::write(__CLASS__);
    }
}
