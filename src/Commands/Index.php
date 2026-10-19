<?php declare(strict_types=1);
/*
 * This file is part of Aplus Framework CLI Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aplus\Cli\Commands;

use Aplus\Cli\Cli;
use Aplus\Cli\Command;
use Aplus\Cli\Styles\ForegroundColor;

/**
 * Class Index.
 *
 * @package cli
 */
class Index extends Command
{
    protected string $name = 'index';
    protected string $description = 'Show commands list';
    protected string $usage = 'index';
    protected array $options = [
        '-g' => 'Shows greeting.',
    ];

    public function run() : void
    {
        $this->showHeader();
        $this->showDate();
        if ($this->console->getOption('g')) {
            $this->greet();
        }
        $this->listCommands();
    }

    public function getDescription() : string
    {
        return $this->console->getLanguage()->render('cli', 'index.description');
    }

    public function getOptions() : array
    {
        return [
            '-g' => $this->console->getLanguage()->render('cli', 'index.option.greet'),
        ];
    }

    protected function listCommands() : void
    {
        $groupDefault = [];
        $groups = [];
        foreach ($this->console->getCommands() as $name => $command) {
            $group = $command->getGroup();
            if ($group === null) {
                $groupDefault[$name] = $command;
                continue;
            }
            $groups[$group][$name] = $command;
        }
        Cli::write(
            $this->console->getLanguage()->render('cli', 'availableCommands') . ':',
            ForegroundColor::yellow
        );
        [$width, $lengths] = $this->getWidthAndLengths($groupDefault);
        foreach ($groupDefault as $name => $command) {
            Cli::write(
                '  ' . Cli::style($name, ForegroundColor::green) . '  '
                // @phpstan-ignore-next-line
                . \str_repeat(' ', $width - $lengths[$name])
                . $this->editDescription($command->getDescription())
            );
        }
        \ksort($groups);
        foreach ($groups as $groupName => $commands) {
            Cli::newLine();
            Cli::write(' ' . $groupName . ':', ForegroundColor::brightYellow);
            [$width, $lengths] = $this->getWidthAndLengths($commands);
            foreach ($commands as $name => $command) {
                Cli::write(
                    '  ' . Cli::style($name, ForegroundColor::green) . '  '
                    // @phpstan-ignore-next-line
                    . \str_repeat(' ', $width - $lengths[$name])
                    . $this->editDescription($command->getDescription())
                );
            }
        }
    }

    protected function editDescription(string $description) : string
    {
        $description = \trim($description);
        if (!\str_ends_with($description, '.')) {
            $description .= '.';
        }
        return $description;
    }

    /**
     * @param array<string,Command> $commands
     *
     * @return array<array<string,int>|int>
     */
    protected function getWidthAndLengths(array $commands) : array
    {
        $width = 0;
        $lengths = [];
        foreach (\array_keys($commands) as $name) {
            $lengths[$name] = \mb_strlen($name);
            if ($lengths[$name] > $width) {
                $width = $lengths[$name];
            }
        }
        return [$width, $lengths];
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
        Cli::write($text, ForegroundColor::green);
    }

    protected function showDate() : void
    {
        $text = $this->console->getLanguage()->date(\time(), 'full');
        $text = \ucfirst($text) . ' - '
            . \date('H:i:s') . ' - '
            . \date_default_timezone_get() . \PHP_EOL;
        Cli::write($text);
    }

    protected function greet() : void
    {
        $hour = \date('H');
        $timing = 'evening';
        if ($hour > 4 && $hour < 12) {
            $timing = 'morning';
        } elseif ($hour > 4 && $hour < 18) {
            $timing = 'afternoon';
        }
        $greeting = $this->console->getLanguage()
            ->render('cli', 'greet.' . $timing, [$this->getUser()]);
        Cli::write($greeting);
        Cli::newLine();
    }

    protected function getUser() : string
    {
        $username = \posix_getlogin();
        if ($username === false) {
            return $this->console->getLanguage()->render('cli', 'friend');
        }
        $info = \posix_getpwnam($username);
        if (!$info) {
            return $username;
        }
        $gecos = $info['gecos'];
        if (!$gecos) {
            return $username;
        }
        $length = \strpos($gecos, ',') ?: \strlen($gecos);
        return \substr($gecos, 0, $length);
    }
}
