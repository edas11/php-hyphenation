<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.4
 * Time: 09.15
 */

namespace Edvardas\Hyphenation\Output;

class ConsoleOutput
{
    public function writeError(string $errorMsg)
    {
        echo "Erros: $errorMsg";
    }

    public function writeInfo(string $infoMsg)
    {
        echo "Info: $infoMsg";
    }

}