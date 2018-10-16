<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.16
 * Time: 10.46
 */
namespace Edvardas\Hyphenation\Hyphenator\Model\MappingStrategy;

use Edvardas\Hyphenation\UtilityComponents\Database\DbDataMappingStrategy;

class WordPatternsMappingStrategy implements DbDataMappingStrategy
{

    public function map(array $dbData): array
    {
        $matchedPatternsResult = [];
        foreach ($dbData as $match) {
            if (array_key_exists($match['word'], $matchedPatternsResult)) {
                array_push($matchedPatternsResult[$match['word']], $match['pattern']);
            } else {
                $matchedPatternsResult[$match['word']] = [$match['pattern']];
            }
        }
        return $matchedPatternsResult;
    }
}