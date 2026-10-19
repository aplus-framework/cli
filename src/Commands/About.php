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
 * Class About.
 *
 * @package cli
 */
class About extends Command
{
    public function run() : void
    {
        $lang = $this->console->getLanguage();
        Cli::write($lang->render('cli', 'about.line1'), ForegroundColor::brightGreen);
        Cli::write($lang->render('cli', 'about.line2'));
        Cli::write($lang->render('cli', 'about.line3'));
        Cli::write($lang->render('cli', 'about.line4'));
        Cli::write($lang->render('cli', 'about.line5'));
    }

    public function getDescription() : string
    {
        return $this->console->getLanguage()->render('cli', 'about.description');
    }
}
