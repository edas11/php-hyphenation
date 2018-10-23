<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.23
 * Time: 11.58
 */

namespace UtilityComponents\Http;

use Edvardas\Hyphenation\UtilityComponents\Http\RouteParts;
use PHPUnit\Framework\TestCase;

class RoutePartsTest extends TestCase
{
    private $routeParts;
    public function setUp()
    {
        $this->routeParts = new RouteParts('path/to/some/place');
    }

    public function testMatchesExactly()
    {
        $patternParts = new RouteParts('/path/to/some/place');
        $isMatches = $this->routeParts->matches($patternParts, '');
        $this->assertTrue($isMatches);
    }

    public function testMatchesWithPattern()
    {
        $patternParts = new RouteParts('/path/to/{value}/place');
        $isMatches = $this->routeParts->matches($patternParts, '{value}');
        $this->assertTrue($isMatches);
    }

    public function testDoesntMatchShorter()
    {
        $patternParts = new RouteParts('/path/{value}/place');
        $isMatches = $this->routeParts->matches($patternParts, '{value}');
        $this->assertFalse($isMatches);
    }

    public function testDoesntMatchLonger()
    {
        $patternParts = new RouteParts('/path/to/{value}/place/in');
        $isMatches = $this->routeParts->matches($patternParts, '{value}');
        $this->assertFalse($isMatches);
    }

    public function testDoesntMatchDifferent()
    {
        $patternParts = new RouteParts('/ppath/tto/{value}/pplace/iin');
        $isMatches = $this->routeParts->matches($patternParts, '{value}');
        $this->assertFalse($isMatches);
    }

    public function testGetPlaceholderValue()
    {
        $patternParts = new RouteParts('/path/to/{value}/place');
        $placeholderValue = $this->routeParts->getPlaceholderValue($patternParts, '{value}');
        $this->assertSame('some', $placeholderValue);
    }
}
