<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.23
 * Time: 09.53
 */

use PHPUnit\Framework\TestCase;
use Edvardas\Hyphenation\UtilityComponents\Cache\MemoryCache;
use Edvardas\Hyphenation\Hyphenator\Algorithm\FullTreeHyphenationAlgorithm;
use Edvardas\Hyphenation\UtilityComponents\Logger\NullLogger;

class FullTreeHyphenationAlgorithmTest extends TestCase
{
    public function testHyphenatesWords()
    {
        $patterns = file(__DIR__.'/patterns-for-tests', FILE_IGNORE_NEW_LINES);
        $algorithm = new FullTreeHyphenationAlgorithm($patterns, new MemoryCache(), new NullLogger());

        $hyphenated = $algorithm->execute('mistranslate');
        $this->assertSame('mis-trans-late', $hyphenated);

        $hyphenated = $algorithm->execute('alphabetical');
        $this->assertSame('al-pha-bet-i-cal', $hyphenated);

        $hyphenated = $algorithm->execute('bewildering');
        $this->assertSame('be-wil-der-ing', $hyphenated);

        $hyphenated = $algorithm->execute('buttons');
        $this->assertSame('but-ton-s', $hyphenated);

        $hyphenated = $algorithm->execute('ceremony');
        $this->assertSame('cer-e-mo-ny', $hyphenated);

        $hyphenated = $algorithm->execute('hovercraft');
        $this->assertSame('hov-er-craft', $hyphenated);

        $hyphenated = $algorithm->execute('lexicographically');
        $this->assertSame('lex-i-co-graph-i-cal-ly', $hyphenated);

        $hyphenated = $algorithm->execute('programmer');
        $this->assertSame('pro-gram-mer', $hyphenated);

        $hyphenated = $algorithm->execute('recursion');
        $this->assertSame('re-cur-sion', $hyphenated);
    }
}
