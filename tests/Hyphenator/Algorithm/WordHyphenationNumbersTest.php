<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.23
 * Time: 10.46
 */

namespace Hyphenator\Algorithm;

use Edvardas\Hyphenation\Hyphenator\Algorithm\PatternHyphenationNumbers;
use Edvardas\Hyphenation\Hyphenator\Algorithm\WordHyphenationNumbers;
use PHPUnit\Framework\TestCase;

class WordHyphenationNumbersTest extends TestCase
{
    private $patternNumbers;
    public function setUp()
    {
        $this->patternNumbers = $this->getMockBuilder(PatternHyphenationNumbers::class)
            ->disableOriginalConstructor()
            ->setMethods(['getNumbersArray'])
            ->getMock();
    }

    public function testCreateFromPatternNumbers()
    {
        $wordGapsLength = 8;
        $matchIndex = 3;
        $this->patternNumbers->method('getNumbersArray')
            ->willReturn([2, 0, 1]);
        $wordNumbers = WordHyphenationNumbers::createFromPatternNumbers($matchIndex, $this->patternNumbers, $wordGapsLength);
        foreach ($wordNumbers as $number) {
            $actual[] = $number;
        }
        $this->assertSame([0, 0, 2, 0, 1, 0, 0, 0], $actual);
    }

    public function testAddWordNumbers()
    {
        $this->patternNumbers->method('getNumbersArray')
            ->will($this->onConsecutiveCalls([2, 0, 1], [3, 0, 1, 2]));
        $wordGapsLength = 8;

        $matchIndex = 3;
        $wordNumbers = WordHyphenationNumbers::createFromPatternNumbers($matchIndex, $this->patternNumbers, $wordGapsLength);

        $matchIndex = 5;
        $wordNumbersToAdd = WordHyphenationNumbers::createFromPatternNumbers($matchIndex, $this->patternNumbers, $wordGapsLength);

        $wordNumbers->addWordNumbers($wordNumbersToAdd);
        foreach ($wordNumbers as $number) {
            $actual[] = $number;
        }
        $this->assertSame([0, 0, 2, 0, 3, 0, 1, 2], $actual);
    }
}
