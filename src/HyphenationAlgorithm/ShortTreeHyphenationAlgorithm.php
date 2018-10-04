<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.2
 * Time: 11.50
 */
namespace Edvardas\Hyphenation\HyphenationAlgorithm;

use Edvardas\Hyphenation\HyphenationAlgorithm\PatternsNodeInTree;
use Edvardas\Hyphenation\HyphenationAlgorithm\AbstractHyphenationAlgorithm;
use Edvardas\Hyphenation\HyphenationAlgorithm\WordHyphenationNumbers;
use Edvardas\Hyphenation\App\App;

class ShortTreeHyphenationAlgorithm extends AbstractHyphenationAlgorithm
{
    public function __construct(array $patterns)
    {
        parent::__construct($patterns);
        App::$logger->info("Started short tree hyphenation algorithm.");
    }

    protected function parsePatternTree(array $patterns): array
    {
        $shortPatternsTree = [];
        foreach ($patterns as $index=>$pattern) {
            $reducedPattern = str_replace(AbstractHyphenationAlgorithm::REDUCE_CHARS, '', $pattern);
            $firstLetter = $reducedPattern[0];
            if (!array_key_exists((string)$firstLetter, $shortPatternsTree)) {
                $shortPatternsTree[(string)$firstLetter] = [];
            }
            array_push($shortPatternsTree[$firstLetter], $pattern);
        }
        return $shortPatternsTree;
    }

    protected function getPossiblePatternWordNumbers(string $inputWord, $pattern, $wordIndex): \Edvardas\Hyphenation\HyphenationAlgorithm\WordHyphenationNumbers
    {
        $reducedPattern = str_replace(AbstractHyphenationAlgorithm::REDUCE_CHARS, '', $pattern);
        $found = stripos($inputWord, $reducedPattern, $wordIndex);
        if ($found !== false) {
            if ($this->begginingOrEndPatternFoundInMiddle($pattern, $reducedPattern, $inputWord, $found)) {
                return new WordHyphenationNumbers(strlen($inputWord) - 1);
            }
            App::$logger->info("Matched pattern $pattern");
            $numberPositionsInPattern = new PatternHyphenationNumbers($pattern);
            $matchedNumbers = WordHyphenationNumbers::createFromPatternNumbers(
                $found,
                $numberPositionsInPattern,
                strlen($inputWord) - 1
            );
        } else {
            $matchedNumbers = new WordHyphenationNumbers(strlen($inputWord) - 1);
        }
        return $matchedNumbers;
    }

    protected function matchedPattern(string $inputWord, int $wordIndex, $patternTree, int $level=0)
    {
        return $patternTree[$inputWord[$wordIndex]];
    }

}
