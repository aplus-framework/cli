<?php declare(strict_types=1);
/*
 * This file is part of Aplus Framework CLI Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Framework\CLI\Commands;

use Framework\CLI\CLI;
use Framework\CLI\Command;

/**
 * Class Index.
 */
class Index extends Command
{
    protected string $name = 'index';
    protected string $description = 'Show commands list';
    protected string $usage = 'index';

    public function run() : void
    {
        $this->showHeader();
        $this->showDate();
        $this->listCommands();
    }

    public function getDescription() : string
    {
        return $this->console->getLanguage()->render('cli', 'index.description');
    }

    protected function listCommands() : void
    {
        $width = 0;
        $lengths = [];
        foreach (\array_keys($this->console->getCommands()) as $name) {
            $lengths[$name] = \mb_strlen($name);
            if ($lengths[$name] > $width) {
                $width = $lengths[$name];
            }
        }
        CLI::write($this->console->getLanguage()->render('cli', 'commands') . ':', 'yellow');
        foreach ($this->console->getCommands() as $name => $command) {
            CLI::write(
                '  ' . CLI::style($name, 'green') . '  '
                . \str_repeat(' ', $width - $lengths[$name])
                . $command->getDescription()
            );
        }
    }

    protected function showHeader() : void
    {
        $text = <<<'EOL'
                _          _              ____ _     ___
               / \   _ __ | |_   _ ___   / ___| |   |_ _|
              / _ \ | '_ \| | | | / __| | |   | |    | |
             / ___ \| |_) | | |_| \__ \ | |___| |___ | |
            /_/   \_\ .__/|_|\__,_|___/  \____|_____|___|
                    |_|

            EOL;
        CLI::write($text, 'green');
    }

    protected function showDate() : void
    {
        $text = $this->console->getLanguage()->date(\time(), 'full');
        $text = \ucfirst($text) . ' - '
            . \date('H:i:s') . ' - '
            . \date_default_timezone_get() . \PHP_EOL;
        CLI::write($text);
    }
}
