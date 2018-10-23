<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.2
 * Time: 11.50
 */
declare(strict_types=1);

namespace Edvardas\Hyphenation\Hyphenator\Algorithm;

use Edvardas\Hyphenation\Hyphenator\Algorithm\PatternsNodeInTree;
use Edvardas\Hyphenation\Hyphenator\Algorithm\WordHyphenationNumbers;
use Edvardas\Hyphenation\Hyphenator\Algorithm\PatternHyphenationNumbers;
use Edvardas\Hyphenation\Hyphenator\Algorithm\AbstractHyphenationAlgorithm;
use Edvardas\Hyphenation\App\App;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

class FullTreeHyphenationAlgorithm extends AbstractHyphenationAlgorithm
{
    public function __construct(array $patterns, LoggerInterface $logger)
    {
        parent::__construct($patterns, $logger);
        $this->logger->info("Started full tree hyphenation algorithm.");
    }

    protected function parsePatternTree(array $groupOfPatterns): array
    {
        $patternsTree = [];
        foreach ($groupOfPatterns as $index => $pattern) {
            $reducedPattern = str_replace(
                AbstractHyphenationAlgorithm::REDUCE_CHARS,
                '', $pattern
            );
            $this->putPatternToTree($pattern, $reducedPattern, $patternsTree);
        }
        return $patternsTree;
    }

    protected function matchedPattern(string $inputWord, int $wordIndex, $patternTree, int $level = 0): array
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
                $this->logger->info("Reached level $level in patterns tree.");
            }
        } else {
            $this->logger->info("Reached level $level in patterns tree.");
        }

        $patternsOfThisLevel->addAll($patternsOfNextLevels->get());
        return $patternsOfThisLevel->get();
    }

    private function putPatternToTree(string $pattern, string $reducedPattern, array &$patternsTree, int $level = 0)
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
            $level + 1
        );
    }

}
