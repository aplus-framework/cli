<?php
/*
 * This file is part of Aplus Framework CLI Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\Cli;

use Aplus\Cli\Cli;
use Aplus\Cli\Command;

class CommandMock extends Command
{
    protected string $name = 'test';
    protected string $description = 'Lorem ipsum';
    protected string $usage = 'test';
    protected array $options = [
        '-b' => 'foo bar',
    ];

    public function run() : void
    {
        Cli::write(\print_r($this->console->getOptions(), true));
        Cli::write(\print_r($this->console->getOption('o'), true));
        Cli::write(\print_r($this->console->getArguments(), true));
        Cli::write(\print_r($this->console->getArgument(1), true));
    }
}
