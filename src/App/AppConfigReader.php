<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.5
 * Time: 16.40
 */

namespace Edvardas\Hyphenation\App;

use Edvardas\Hyphenation\UtilityComponents\Config\Config;

class AppConfigReader
{
    public static function read(string $pathToConfig): Config
    {
        $configData = require($pathToConfig);
        return new Config($configData);
    }
}