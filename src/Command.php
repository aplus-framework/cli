<?php declare(strict_types=1);
/*
 * This file is part of The Framework CLI Library.
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
     * @param Console $console
     */
    public function __construct(Console $console)
    {
        $this->console = $console;
    }

    /**
     * Run the command.
     */
    abstract public function run() : void;

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
     * Tells if the command is active.
     *
     * @return bool
     */
    #[Pure]
    public function isActive() : bool
    {
        return $this->active;
    }
}
