<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.12
 * Time: 12.02
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\File;

use Edvardas\Hyphenation\App\App;
use Psr\Log\LoggerInterface;

class PatternsFile
{
    public static function getContentsAsArray(string $patternsFileName, LoggerInterface $logger): array
    {
        $patterns = file($patternsFileName, FILE_IGNORE_NEW_LINES);
        if ($patterns === false) {
            $logger->error("Could not read patterns file.");
            exit;
        }
        return $patterns;
    }
}