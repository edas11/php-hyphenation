<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.2
 * Time: 11.50
 */
namespace Edvardas\Hyphenation\HyphenationAlgorithm;

use Edvardas\Hyphenation\HyphenationAlgorithm\PatternsNodeInTree;
use Edvardas\Hyphenation\HyphenationAlgorithm\WordHyphenationNumbers;
use Edvardas\Hyphenation\HyphenationAlgorithm\PatternHyphenationNumbers;
use Edvardas\Hyphenation\HyphenationAlgorithm\AbstractHyphenationAlgorithm;

class FullTreeHyphenationAlgorithm extends AbstractHyphenationAlgorithm
{
    public function __construct(array $patterns)
    {
        parent::__construct($patterns);
        \Edvardas\Hyphenation\App\App::$logger->info("Started full tree hyphenation algorithm.");
    }

    protected function parsePatternTree(array $patterns): array
    {
        $patternsTree = [];
        foreach ($patterns as $index=>$pattern) {
            $reducedPattern = str_replace(
                AbstractHyphenationAlgorithm::REDUCE_CHARS,
                '', $pattern
            );
            $this->putPatternToTree($pattern, $reducedPattern, $patternsTree);
        }
        return $patternsTree;
    }

    protected function getPossiblePatternWordNumbers(string $inputWord, $pattern, $wordIndex): WordHyphenationNumbers
    {
        $reducedPattern = str_replace(AbstractHyphenationAlgorithm::REDUCE_CHARS, '', $pattern);
        if ($this->begginingOrEndPatternFoundInMiddle($pattern, $reducedPattern, $inputWord, $wordIndex)) {
            return new WordHyphenationNumbers(strlen($inputWord) - 1);
        }
        \Edvardas\Hyphenation\App\App::$logger->info("Matched pattern $pattern");
        $numberPositionsInPattern = new PatternHyphenationNumbers($pattern);
        $matchedNumbers = WordHyphenationNumbers::createFromPatternNumbers(
            $wordIndex,
            $numberPositionsInPattern,
            strlen($inputWord) - 1
        );
        return $matchedNumbers;
    }

    protected function matchedPattern(string $inputWord, int $wordIndex, $patternTree, int $level=0)
    {
        $currentIndex = $wordIndex + $level;
        if ($currentIndex > strlen($inputWord)) {
            return [];
        }

        $patternsOfThisLevel = new PatternsNodeInTree();
        if (array_key_exists(0, $patternTree)) {
            $patternsOfThisLevel->addAll($patternTree[0]->get());
        }

        $patternsOfNextLevels = new PatternsNodeInTree();
        if ($currentIndex < strlen($inputWord)) {
            $letter = $inputWord[$currentIndex];
            if (array_key_exists($letter, $patternTree)) {
                $patternsOfNextLevels->addAll(
                    $this->matchedPattern(
                        $inputWord,
                        $wordIndex,
                        $patternTree[(string)$letter],
                        $level + 1
                    )
                );
            } else {
                \Edvardas\Hyphenation\App\App::$logger->info("Reached level $level in patterns tree.");
            }
        } else {
            \Edvardas\Hyphenation\App\App::$logger->info("Reached level $level in patterns tree.");
        }

        $patternsOfThisLevel->addAll($patternsOfNextLevels->get());
        return $patternsOfThisLevel->get();
    }

    private function putPatternToTree(string $pattern, string $reducedPattern, array &$patternsTree, int $level=0)
    {
        if ($level === strlen($reducedPattern)) {
            if (!array_key_exists(0, $patternsTree)) {
                $patternsTree[0] = new PatternsNodeInTree();
            }
            $patternsTree[0]->add($pattern);
            return;
        }
        $letter = (string)$reducedPattern[$level];
        if (!array_key_exists($letter, $patternsTree)) {
            $patternsTree[$letter] = [];
        }
        $this->putPatternToTree(
            $pattern,
            $reducedPattern,
            $patternsTree[$letter],
            $level+1
        );
    }

}
