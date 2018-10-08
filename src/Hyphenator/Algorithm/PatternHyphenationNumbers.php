<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.3
 * Time: 15.38
 */
declare(strict_types=1);

namespace Edvardas\Hyphenation\Hyphenator\Algorithm;

use Edvardas\Hyphenation\Hyphenator\Algorithm\HyphenationNumbers;

class PatternHyphenationNumbers extends HyphenationNumbers
{
    protected $numbersArray;

    public function __construct(string $pattern)
    {
        $this->numbersArray = $this->numberPositionsInPattern($pattern);
    }

    protected function getNumbersArray(): array
    {
        return $this->numbersArray;
    }

    // pattern gap index => pattern number. Pattern is considered to start with a gap.
    private function numberPositionsInPattern(string $pattern): array
    {
        $patternNoPoint = str_replace('.', '', $pattern);
        $patternExpaned = $this->expandPatternGaps($patternNoPoint);

        $numberPos = [];
        for ($j = 0; $j < strlen($patternExpaned); $j++) {
            if (is_numeric($patternExpaned[$j])) {
                $numberPos[$j / 2] = $patternExpaned[$j];
            };
        }
        return $numberPos;
    }

    private function expandPatternGaps($patternNoPoint): string
    {
        if (!is_numeric($patternNoPoint[0])) {
            $patternExpaned = ' ';
        } else {
            $patternExpaned = '';
        }
        for ($i = 0; $i < strlen($patternNoPoint); $i++) {
            if (is_numeric($patternNoPoint[$i])) {
                $patternExpaned = $patternExpaned . $patternNoPoint[$i];
            } elseif (
                $i + 1 < strlen($patternNoPoint)
                && !is_numeric($patternNoPoint[$i + 1])
            ) {
                $patternExpaned = $patternExpaned . $patternNoPoint[$i] . ' ';
            } else {
                $patternExpaned = $patternExpaned . $patternNoPoint[$i];
            }
        }
        return $patternExpaned;
    }
}
