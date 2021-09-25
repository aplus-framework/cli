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
 * Class Help.
 *
 * @package cli
 */
class Help extends Command
{
    protected string $name = 'help';
    protected string $usage = 'help [command_name]';

    public function run() : void
    {
        $command = $this->console->getArgument(0) ?? 'help';
        $this->showCommand($command);
    }

    protected function showCommand(string $commandName) : void
    {
        $command = $this->console->getCommand($commandName);
        if ($command === null) {
            CLI::error(
                $this->console->getLanguage()->render('cli', 'commandNotFound', [$commandName])
            );
        }
        CLI::write(CLI::style(
            $this->console->getLanguage()->render('cli', 'command') . ': ',
            CLI::FG_GREEN
        ) . $command->getName());
        $value = $command->getDescription();
        if ($value !== '') {
            CLI::write(CLI::style(
                $this->console->getLanguage()->render('cli', 'description') . ': ',
                CLI::FG_GREEN
            ) . $value);
        }
        $value = $command->getUsage();
        if ($value !== '') {
            CLI::write(CLI::style(
                $this->console->getLanguage()->render('cli', 'usage') . ': ',
                CLI::FG_GREEN
            ) . $value);
        }
        $value = $command->getOptions();
        if ($value) {
            CLI::write(
                $this->console->getLanguage()->render('cli', 'options') . ': ',
                CLI::FG_GREEN
            );
            $lastKey = \array_key_last($value);
            foreach ($value as $option => $description) {
                CLI::write('  ' . $this->renderOption($option));
                CLI::write('  ' . $description);
                if ($option !== $lastKey) {
                    CLI::newLine();
                }
            }
        }
    }

    protected function renderOption(string $text) : string
    {
        $text = \trim(\preg_replace('/\s+/', '', $text));
        $text = \explode(',', $text);
        \sort($text);
        foreach ($text as &$item) {
            $item = CLI::style($item, CLI::FG_YELLOW);
        }
        return \implode(', ', $text);
    }

    public function getDescription() : string
    {
        return $this->console->getLanguage()->render('cli', 'help.description');
    }
}
