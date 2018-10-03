<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.2
 * Time: 11.50
 */
namespace Edvardas\Hyphenation\HyphenationAlgorithm;
use Edvardas\Hyphenation\HyphenationAlgorithm\Patterns;
use Edvardas\Hyphenation\HyphenationAlgorithm\WordHyphenationNumbers;
use Edvardas\Hyphenation\HyphenationAlgorithm\PatternHyphenationNumbers;
use Edvardas\Hyphenation\HyphenationAlgorithm\AbstractHyphenationAlgorithm;

class FullTreeHyphenationAlgorithm extends AbstractHyphenationAlgorithm
{

    protected function getWordHyphenationNumbers(string $inputWord): WordHyphenationNumbers
    {
        $matchedNumbersAll = new WordHyphenationNumbers(strlen($inputWord) - 1);
        for ( $wordIndex=0; $wordIndex<strlen($inputWord); $wordIndex++ ) {
            $patterns=$this->matchedPattern($inputWord, $wordIndex, $this->patternTree);
                foreach ($patterns as $pattern) {
                    $reducedPattern = str_replace(AbstractHyphenationAlgorithm::REDUCE_CHARS, '', $pattern);
                    if ($this->beginingOrEndPatternFoundInMiddle($pattern, $reducedPattern, $inputWord, $wordIndex)) {
                        continue;
                    }
                    $numberPositionsInPattern = new PatternHyphenationNumbers($pattern);
                    $matchedNumbers = WordHyphenationNumbers::createFromPatternNumbers($wordIndex, $numberPositionsInPattern, strlen($inputWord) - 1);
                    $matchedNumbersAll->addWordNumbers($matchedNumbers);
                }
        }
        return $matchedNumbersAll;
    }

    protected function parsePatternTree(array $patterns): array {
        $patternsTree = [];
        foreach ($patterns as $index=>$pattern) {
            $reducedPattern = str_replace(AbstractHyphenationAlgorithm::REDUCE_CHARS, '', $pattern);
            $this->putPatternToTree($pattern, $reducedPattern, $patternsTree);
        }
        return $patternsTree;
    }

    private function putPatternToTree(string $pattern, string $reducedPattern, array &$patternsTree, int $level=0) {
        if ($level === strlen($reducedPattern)) {
            if (!array_key_exists(0, $patternsTree)) {
                $patternsTree[0] = new Patterns();
            }
            $patternsTree[0]->add($pattern);
            return;
        }
        $letter = (string)$reducedPattern[$level];
        if (!array_key_exists( $letter, $patternsTree )) {
            $patternsTree[$letter] = [];
        }
        $this->putPatternToTree($pattern, $reducedPattern, $patternsTree[$letter], $level+1);
    }

    private function matchedPattern(string $inputWord, int $wordIndex, $patternTree, int $level=0) {
        $currentIndex = $wordIndex + $level;
        if ($currentIndex > strlen($inputWord)) {
            return [];
        }

        $patternsOfThisLevel = new Patterns();
        if (array_key_exists(0, $patternTree)) {
            $patternsOfThisLevel->addAll($patternTree[0]->get());
        }

        $patternsOfNextLevels = new Patterns();
        if ($currentIndex < strlen($inputWord)) {
            $letter = $inputWord[$currentIndex];
            if (array_key_exists($letter, $patternTree)) {
                $patternsOfNextLevels->addAll(
                    $this->matchedPattern($inputWord, $wordIndex, $patternTree[(string)$letter], $level + 1)
                );
            }
        }

        $patternsOfThisLevel->addAll($patternsOfNextLevels->get());
        return $patternsOfThisLevel->get();
    }

}