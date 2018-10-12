<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.12
 * Time: 12.10
 */

namespace Edvardas\Hyphenation\Hyphenator\File;


class WordsFile
{
    public static function getContentsAsArray(string $wordsFileName): array
    {
        $words = file($wordsFileName, FILE_IGNORE_NEW_LINES);
        if ($words === false) {
            App::$logger->error("Could not read $wordsFileName file.");
            exit;
        }
        return $words;
    }
}