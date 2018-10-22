<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.16
 * Time: 10.46
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Model\MappingStrategy;

use Edvardas\Hyphenation\UtilityComponents\Database\DbDataMappingStrategy;

class WordPatternsMappingStrategy implements DbDataMappingStrategy
{

    public function map(array $dbData): array
    {
        $matchedPatternsResult = [];
        foreach ($dbData as $match) {
            if (!array_key_exists($match['word'], $matchedPatternsResult)) {
                $matchedPatternsResult[$match['word']] = [];
            }
            $matchedPatternsResult[$match['word']][] = $match['pattern'];
        }
        return $matchedPatternsResult;
    }
}