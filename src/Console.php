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

use Framework\CLI\Commands\About;
use Framework\CLI\Commands\Help;
use Framework\CLI\Commands\Index;
use Framework\Language\Language;
use JetBrains\PhpStorm\Pure;

/**
 * Class Console.
 *
 * @package cli
 */
class Console
{
    /**
     * List of commands.
     *
     * @var array<string,Command> The command name as key and the object as value
     */
    protected array $commands = [];
    /**
     * The current command name.
     */
    protected string $command = '';
    /**
     * Input options.
     *
     * @var array<string,bool|string> The option value as string or TRUE if it
     * was passed without a value
     */
    protected array $options = [];
    /**
     * Input arguments.
     *
     * @var array<int,string>
     */
    protected array $arguments = [];
    /**
     * The Language instance.
     */
    protected Language $language;

    /**
     * Console constructor.
     *
     * @param Language|null $language
     */
    public function __construct(Language $language = null)
    {
        if ($language === null) {
            $language = new Language('en');
        }
        $this->language = $language->addDirectory(__DIR__ . '/Languages');
        global $argv;
        $this->prepare($argv ?? []);
    }

    /**
     * Get all CLI options.
     *
     * @return array<string,bool|string>
     */
    #[Pure]
    public function getOptions() : array
    {
        return $this->options;
    }

    /**
     * Get a specific option or null.
     *
     * @param string $option
     *
     * @return bool|string|null The option value as string, TRUE if it
     * was passed without a value or NULL if the option was not set
     */
    #[Pure]
    public function getOption(string $option) : bool | string | null
    {
        return $this->options[$option] ?? null;
    }

    /**
     * Get all arguments.
     *
     * @return array<int,string>
     */
    #[Pure]
    public function getArguments() : array
    {
        return $this->arguments;
    }

    /**
     * Get a specific argument or null.
     *
     * @param int $position Argument position, starting from zero
     *
     * @return string|null The argument value or null if it was not set
     */
    #[Pure]
    public function getArgument(int $position) : ?string
    {
        return $this->arguments[$position] ?? null;
    }

    /**
     * Get the Language instance.
     *
     * @return Language
     */
    #[Pure]
    public function getLanguage() : Language
    {
        return $this->language;
    }

    /**
     * Add a command to the console.
     *
     * @param class-string<Command>|Command $command A Command instance or the class FQN
     *
     * @return static
     */
    public function addCommand(Command | string $command) : static
    {
        if (\is_string($command)) {
            $command = new $command();
        }
        $command->setConsole($this);
        $this->commands[$command->getName()] = $command;
        return $this;
    }

    /**
     * Add many commands to the console.
     *
     * @param array<class-string<Command>|Command> $commands A list of Command
     * instances or the classes FQN
     *
     * @return static
     */
    public function addCommands(array $commands) : static
    {
        foreach ($commands as $command) {
            $this->addCommand($command);
        }
        return $this;
    }

    /**
     * Get an active command.
     *
     * @param string $name Command name
     *
     * @return Command|null The Command on success or null if not found
     */
    public function getCommand(string $name) : ?Command
    {
        if (isset($this->commands[$name]) && $this->commands[$name]->isActive()) {
            return $this->commands[$name];
        }
        return null;
    }

    /**
     * Get a list of active commands.
     *
     * @return array<string,Command>
     */
    public function getCommands() : array
    {
        $commands = $this->commands;
        foreach ($commands as $name => $command) {
            if ( ! $command->isActive()) {
                unset($commands[$name]);
            }
        }
        \ksort($commands);
        return $commands;
    }

    /**
     * Run the Console.
     */
    public function run() : void
    {
        if ($this->getCommand('index') === null) {
            $this->addCommand(new Index($this));
        }
        if ($this->getCommand('help') === null) {
            $this->addCommand(new Help($this));
        }
        if ($this->getCommand('about') === null) {
            $this->addCommand(new About($this));
        }
        if ($this->command === '') {
            $this->command = 'index';
        }
        $command = $this->getCommand($this->command);
        if ($command === null) {
            CLI::error(CLI::style(
                $this->getLanguage()->render('cli', 'commandNotFound', [$this->command]),
                CLI::FG_BRIGHT_RED
            ));
        }
        $command->run();
    }

    public function exec(string $command) : void
    {
        $argumentValues = static::commandToArgs($command);
        \array_unshift($argumentValues, 'removed');
        $this->prepare($argumentValues);
        $this->run();
    }

    protected function reset() : void
    {
        $this->command = '';
        $this->options = [];
        $this->arguments = [];
    }

    /**
     * Prepare information of the command line.
     *
     * [options] [arguments] [options]
     * [options] -- [arguments]
     * [command]
     * [command] [options] [arguments] [options]
     * [command] [options] -- [arguments]
     * Short option: -l, -la === l = true, a = true
     * Long option: --list, --all=vertical === list = true, all = vertical
     * Only Long Options receive values:
     * --foo=bar or --f=bar - "foo" and "f" are bar
     * -foo=bar or -f=bar - all characters are true (f, o, =, b, a, r)
     * After -- all values are arguments, also if is prefixed with -
     * Without --, arguments and options can be mixed: -ls foo -x abc --a=e.
     *
     * @param array<int,string> $argumentValues
     */
    protected function prepare(array $argumentValues) : void
    {
        $this->reset();
        unset($argumentValues[0]);
        if (isset($argumentValues[1]) && $argumentValues[1][0] !== '-') {
            $this->command = $argumentValues[1];
            unset($argumentValues[1]);
        }
        $endOptions = false;
        foreach ($argumentValues as $value) {
            if ($endOptions === false && $value === '--') {
                $endOptions = true;
                continue;
            }
            if ($endOptions === false && $value[0] === '-') {
                if (isset($value[1]) && $value[1] === '-') {
                    $option = \substr($value, 2);
                    if (\str_contains($option, '=')) {
                        [$option, $value] = \explode('=', $option, 2);
                        $this->options[$option] = $value;
                        continue;
                    }
                    $this->options[$option] = true;
                    continue;
                }
                foreach (\str_split(\substr($value, 1)) as $item) {
                    $this->options[$item] = true;
                }
                continue;
            }
            //$endOptions = true;
            $this->arguments[] = $value;
        }
    }

    /**
     * @param string $command
     *
     * @see https://someguyjeremy.com/2017/07/adventures-in-parsing-strings-to-argv-in-php.html
     *
     * @return array<int,string>
     */
    #[Pure]
    public static function commandToArgs(string $command) : array
    {
        $charCount = \strlen($command);
        $argv = [];
        $arg = '';
        $inDQuote = false;
        $inSQuote = false;
        for ($i = 0; $i < $charCount; $i++) {
            $char = $command[$i];
            if ($char === ' ' && ! $inDQuote && ! $inSQuote) {
                if ($arg !== '') {
                    $argv[] = $arg;
                }
                $arg = '';
                continue;
            }
            if ($inSQuote && $char === "'") {
                $inSQuote = false;
                continue;
            }
            if ($inDQuote && $char === '"') {
                $inDQuote = false;
                continue;
            }
            if ($char === '"' && ! $inSQuote) {
                $inDQuote = true;
                continue;
            }
            if ($char === "'" && ! $inDQuote) {
                $inSQuote = true;
                continue;
            }
            $arg .= $char;
        }
        $argv[] = $arg;
        return $argv;
    }
}
