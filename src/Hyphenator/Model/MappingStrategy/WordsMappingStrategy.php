<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.16
 * Time: 10.46
 */
namespace Edvardas\Hyphenation\Hyphenator\Model\MappingStrategy;

use Edvardas\Hyphenation\Hyphenator\Model\Words;
use Edvardas\Hyphenation\UtilityComponents\Database\DbDataMappingStrategy;

class WordsMappingStrategy implements DbDataMappingStrategy
{

    public function map(array $dbData): array
    {
        return array_combine(
            array_column($dbData, Words::WORD_COLUMN),
            array_column($dbData, Words::HYPHENATED_WORD_COLUMN)
        );
    }
}