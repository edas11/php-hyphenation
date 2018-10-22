<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.22
 * Time: 14.02
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\UtilityComponents\Http;

class RouteParts
{
    private $partsArray;

    private const PARTS_MATCH = 0;
    private const PARTS_NOT_MATCH = 1;

    public function __construct(string $routeString)
    {
        $this->partsArray = explode('/', trim($routeString, '/'));
    }

    public function matches(RouteParts $routePatternParts, string $allowedPlaceholder): bool
    {
        $routeAndPatternDifference = array_udiff_assoc(
            $this->partsArray,
            $routePatternParts->partsArray,
            function($routePart, $patternPart) use ($allowedPlaceholder) {
                if ($routePart === $patternPart) return self::PARTS_MATCH;
                if ($patternPart === $allowedPlaceholder) return self::PARTS_MATCH;
                else return self::PARTS_NOT_MATCH;
            }
        );

        return count($routeAndPatternDifference) === 0;
    }

    public function getPlaceholderValue(RouteParts $patternWithPlaceholder, string $placeholder): string
    {
        $pathParamIndex = array_search($placeholder, $patternWithPlaceholder->partsArray);
        return $this->partsArray[$pathParamIndex];
    }
}