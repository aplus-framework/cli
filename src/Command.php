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

use JetBrains\PhpStorm\Pure;

/**
 * Class Command.
 *
 * @package cli
 */
abstract class Command
{
    /**
     * Console instance of the current command.
     */
    protected Console $console;
    /**
     * Command name.
     */
    protected string $name;
    /**
     * Command description.
     */
    protected string $description;
    /**
     * Command usage.
     */
    protected string $usage = 'command [options] -- [arguments]';
    /**
     * Command options.
     *
     * @var array<string,string>
     */
    protected array $options = [];
    /**
     * Tells if command is active.
     */
    protected bool $active = true;

    /**
     * Command constructor.
     *
     * @param Console|null $console
     */
    public function __construct(Console $console = null)
    {
        if ($console) {
            $this->console = $console;
        }
    }

    /**
     * Run the command.
     */
    abstract public function run() : void;

    /**
     * Get console instance.
     *
     * @return Console
     */
    public function getConsole() : Console
    {
        return $this->console;
    }

    /**
     * Set console instance.
     *
     * @param Console $console
     *
     * @return static
     */
    public function setConsole(Console $console) : static
    {
        $this->console = $console;
        return $this;
    }

    /**
     * Get command name.
     *
     * @return string
     */
    public function getName() : string
    {
        if (isset($this->name)) {
            return $this->name;
        }
        $name = static::class;
        $pos = \strrpos($name, '\\');
        if ($pos !== false) {
            $name = \substr($name, $pos + 1);
        }
        if (\str_ends_with($name, 'Command')) {
            $name = \substr($name, 0, -7);
        }
        $name = \strtolower($name);
        return $this->name = $name;
    }

    /**
     * Set command name.
     *
     * @param string $name
     *
     * @return static
     */
    public function setName(string $name) : static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get command description.
     *
     * @return string
     */
    public function getDescription() : string
    {
        if (isset($this->description)) {
            return $this->description;
        }
        $description = $this->console->getLanguage()->render('cli', 'noDescription');
        return $this->description = $description;
    }

    /**
     * Set command description.
     *
     * @param string $description
     *
     * @return static
     */
    public function setDescription(string $description) : static
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get command usage.
     *
     * @return string
     */
    #[Pure]
    public function getUsage() : string
    {
        return $this->usage;
    }

    /**
     * Set command usage.
     *
     * @param string $usage
     *
     * @return static
     */
    public function setUsage(string $usage) : static
    {
        $this->usage = $usage;
        return $this;
    }

    /**
     * Get command options.
     *
     * @return array<string,string>
     */
    #[Pure]
    public function getOptions() : array
    {
        return $this->options;
    }

    /**
     * Set command options.
     *
     * @param array<string,string> $options
     *
     * @return static
     */
    public function setOptions(array $options) : static
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Tells if the command is active.
     *
     * @return bool
     */
    #[Pure]
    public function isActive() : bool
    {
        return $this->active;
    }

    /**
     * Activate the command.
     *
     * @return static
     */
    public function activate() : static
    {
        $this->active = true;
        return $this;
    }

    /**
     * Deactivate the command.
     *
     * @return static
     */
    public function deactivate() : static
    {
        $this->active = false;
        return $this;
    }
}
