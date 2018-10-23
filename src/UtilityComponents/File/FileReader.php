<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.23
 * Time: 16.25
 */

namespace Edvardas\Hyphenation\UtilityComponents\File;

use Psr\Log\LoggerInterface;

class FileReader
{
    public function read(string $fileName, LoggerInterface $logger): array
    {
        $words = file($fileName, FILE_IGNORE_NEW_LINES);
        if ($words === false) {
            $logger->error("Could not read $fileName file.");
            exit;
        }
        return $words;
    }
}