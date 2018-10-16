<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.16
 * Time: 10.46
 */
namespace Edvardas\Hyphenation\Hyphenator\Model\MappingStrategy;

use Edvardas\Hyphenation\UtilityComponents\Database\DbDataMappingStrategy;

class PatternsMappingStrategy implements DbDataMappingStrategy
{

    public function map(array $dbData): array
    {
        return array_column($dbData, 'pattern');
    }
}