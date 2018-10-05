<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.2
 * Time: 11.50
 */
declare(strict_types=1);

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
        foreach ($patterns as $index => $pattern) {
            $reducedPattern = str_replace(AbstractHyphenationAlgorithm::REDUCE_CHARS, '', $pattern);
            $firstLetter = $reducedPattern[0];
            if (!array_key_exists((string)$firstLetter, $shortPatternsTree)) {
                $shortPatternsTree[(string)$firstLetter] = [];
            }
            array_push($shortPatternsTree[$firstLetter], $pattern);
        }
        return $shortPatternsTree;
    }

    protected function matchedPattern(string $inputWord, int $wordIndex, $patternTree, int $level = 0): array
    {
        $patterns = $patternTree[$inputWord[$wordIndex]];
        foreach ($patterns as $index => $pattern) {
            $reducedPattern = str_replace(AbstractHyphenationAlgorithm::REDUCE_CHARS, '', $pattern);
            $found = stripos($inputWord, $reducedPattern, $wordIndex);
            if ($found !== $wordIndex) {
                unset($patterns[$index]);
            }
        }
        return $patterns;
    }

}
