<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.12
 * Time: 12.02
 */

namespace Edvardas\Hyphenation\Hyphenator\File;

use Edvardas\Hyphenation\App\App;

class PatternsFile
{
    public static function getContentsAsArray(string $patternsFileName): array
    {
        $patterns = file($patternsFileName, FILE_IGNORE_NEW_LINES);
        if ($patterns === false) {
            App::$logger->error("Could not read patterns file.");
            exit;
        }
        return $patterns;
    }
}