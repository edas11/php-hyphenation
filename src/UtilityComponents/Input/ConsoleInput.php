<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.4
 * Time: 16.36
 */

namespace Edvardas\Hyphenation\UtilityComponents\Input;

use Edvardas\Hyphenation\App\App;

class ConsoleInput
{
    public function getWords(): array
    {
        global $argv;
        if (count($argv) > 1) {
            for ($i = 1; $i < count($argv); $i++) {
                $inputWords[$i - 1] = $argv[$i];
            }
        } else {
            App::$logger->info("Reading words from words.txt file.");
            $inputWords = file('words.txt', FILE_IGNORE_NEW_LINES);
            if ($inputWords === false) {
                App::$logger->error("Could not read words.txt file.");
                exit;
            }
        }
        return $inputWords;
    }
}