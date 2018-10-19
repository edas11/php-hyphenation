<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.12
 * Time: 12.10
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\File;

use Psr\Log\LoggerInterface;

class WordsFile
{
    public static function getContentsAsArray(string $wordsFileName, LoggerInterface $logger): array
    {
        $words = file($wordsFileName, FILE_IGNORE_NEW_LINES);
        if ($words === false) {
            $logger->error("Could not read $wordsFileName file.");
            exit;
        }
        return $words;
    }
}