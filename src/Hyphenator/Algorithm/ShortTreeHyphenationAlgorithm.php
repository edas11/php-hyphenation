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
use Edvardas\Hyphenation\Hyphenator\Algorithm\AbstractHyphenationAlgorithm;
use Edvardas\Hyphenation\Hyphenator\Algorithm\WordHyphenationNumbers;
use Edvardas\Hyphenation\App\App;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

class ShortTreeHyphenationAlgorithm extends AbstractHyphenationAlgorithm
{
    public function __construct(array $patterns, LoggerInterface $logger)
    {
        parent::__construct($patterns, $logger);
        $this->logger->info("Started short tree hyphenation algorithm.");
    }

    protected function parsePatternTree(array $groupOfPatterns): array
    {
        $shortPatternsTree = [];
        foreach ($groupOfPatterns as $index => $pattern) {
            $reducedPattern = str_replace(AbstractHyphenationAlgorithm::REDUCE_CHARS, '', $pattern);
            $firstLetter = $reducedPattern[0];
            if (!array_key_exists((string)$firstLetter, $shortPatternsTree)) {
                $shortPatternsTree[(string)$firstLetter] = [];
            }
            $shortPatternsTree[$firstLetter][] = $pattern;
        }
        return $shortPatternsTree;
    }

    protected function matchedPattern(string $inputWord, int $wordIndex, $patternTree, int $level = 0): array
    {
        $groupOfPatterns = $patternTree[$inputWord[$wordIndex]];
        foreach ($groupOfPatterns as $index => $pattern) {
            $reducedPattern = str_replace(AbstractHyphenationAlgorithm::REDUCE_CHARS, '', $pattern);
            $found = stripos($inputWord, $reducedPattern, $wordIndex);
            if ($found !== $wordIndex) {
                unset($groupOfPatterns[$index]);
            }
        }
        return $groupOfPatterns;
    }

}
