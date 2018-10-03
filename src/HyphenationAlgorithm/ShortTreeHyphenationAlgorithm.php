<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.2
 * Time: 11.50
 */
namespace Edvardas\Hyphenation\HyphenationAlgorithm;
use Edvardas\Hyphenation\HyphenationAlgorithm\Patterns;
use Edvardas\Hyphenation\HyphenationAlgorithm\AbstractHyphenationAlgorithm;
use Edvardas\Hyphenation\HyphenationAlgorithm\WordHyphenationNumbers;

class ShortTreeHyphenationAlgorithm extends AbstractHyphenationAlgorithm
{

    protected function getWordHyphenationNumbers(string $inputWord): WordHyphenationNumbers
    {
        $matchedNumbersAll = new WordHyphenationNumbers(strlen($inputWord) - 1);
        for ( $wordIndex=0; $wordIndex<strlen($inputWord); $wordIndex++ ) {
            $possiblePatterns = $this->patternTree[$inputWord[$wordIndex]];
            foreach ($possiblePatterns as $pattern) {
                $reducedPattern = str_replace(AbstractHyphenationAlgorithm::REDUCE_CHARS, '', $pattern);
                $found = stripos($inputWord, $reducedPattern, $wordIndex);
                if ($found !== false) {
                    if ($this->beginingOrEndPatternFoundInMiddle($pattern, $reducedPattern, $inputWord, $found)) {
                        continue;
                    }
                    $numberPositionsInPattern = new PatternHyphenationNumbers($pattern);
                    $matchedNumbers = WordHyphenationNumbers::createFromPatternNumbers($found, $numberPositionsInPattern, strlen($inputWord) - 1);
                    $matchedNumbersAll->addWordNumbers($matchedNumbers);
                }

            }
        }
        return $matchedNumbersAll;
    }


    protected function parsePatternTree(array $patterns): array {
        $shortPatternsTree = [];
        foreach ($patterns as $index=>$pattern) {
            $reducedPattern = str_replace(AbstractHyphenationAlgorithm::REDUCE_CHARS, '', $pattern);
            $firstLetter = $reducedPattern[0];
            if (!array_key_exists( (string)$firstLetter, $shortPatternsTree )) {
                $shortPatternsTree[(string)$firstLetter] = [];
            }
            array_push($shortPatternsTree[$firstLetter], $pattern);
        }
        return $shortPatternsTree;
    }

}