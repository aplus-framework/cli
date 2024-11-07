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
use Framework\CLI\Styles\ForegroundColor;

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
                $this->console->getLanguage()->render('cli', 'commandNotFound', [$commandName]),
                \defined('TESTING') ? null : 1
            );
            return;
        }
        CLI::write(CLI::style(
            $this->console->getLanguage()->render('cli', 'command') . ': ',
            ForegroundColor::green
        ) . $command->getName());
        $value = $command->getGroup();
        if ($value !== null) {
            CLI::write(CLI::style(
                $this->console->getLanguage()->render('cli', 'group') . ': ',
                ForegroundColor::green
            ) . $value);
        }
        $value = $command->getDescription();
        if ($value !== '') {
            CLI::write(CLI::style(
                $this->console->getLanguage()->render('cli', 'description') . ': ',
                ForegroundColor::green
            ) . $value);
        }
        $value = $command->getUsage();
        if ($value !== '') {
            CLI::write(CLI::style(
                $this->console->getLanguage()->render('cli', 'usage') . ': ',
                ForegroundColor::green
            ) . $value);
        }
        $value = $command->getOptions();
        if ($value) {
            CLI::write(
                $this->console->getLanguage()->render('cli', 'options') . ': ',
                ForegroundColor::green
            );
            $newOptions = [];
            foreach ($value as $options => $description) {
                $options = $this->sortOptions($options);
                $newOptions[$options] = $description;
            }
            \ksort($newOptions);
            $lastKey = \array_key_last($newOptions);
            foreach ($newOptions as $option => $description) {
                CLI::write('  ' . $this->setColor($option));
                $description = \trim($description);
                if (!\str_ends_with($description, '.')) {
                    $description .= '.';
                }
                CLI::write('  ' . $description);
                if ($option !== $lastKey) {
                    CLI::newLine();
                }
            }
        }
    }

    protected function sortOptions(string $text) : string
    {
        $text = \trim(\preg_replace('/\s+/', '', $text));
        $text = \explode(',', $text);
        \sort($text);
        return \implode(',', $text);
    }

    protected function setColor(string $text) : string
    {
        $text = \explode(',', $text);
        foreach ($text as &$item) {
            $item = CLI::style($item, ForegroundColor::yellow);
        }
        return \implode(', ', $text);
    }

    public function getDescription() : string
    {
        return $this->console->getLanguage()->render('cli', 'help.description');
    }
}
