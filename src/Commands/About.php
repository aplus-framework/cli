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
 * Class About.
 *
 * @package cli
 */
class About extends Command
{
    public function run() : void
    {
        $lang = $this->console->getLanguage();
        CLI::write($lang->render('cli', 'about.line1'), CLI::FG_BRIGHT_GREEN);
        CLI::write($lang->render('cli', 'about.line2'));
        CLI::write($lang->render('cli', 'about.line3'));
        CLI::write($lang->render('cli', 'about.line4'));
        CLI::write($lang->render('cli', 'about.line5'));
    }

    public function getDescription() : string
    {
        return $this->console->getLanguage()->render('cli', 'about.description');
    }
}
