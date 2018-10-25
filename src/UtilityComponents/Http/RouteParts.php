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

    public function __construct(string $routeString)
    {
        $this->partsArray = explode('/', trim($routeString, '/'));
    }

    public function matches(RouteParts $routePatternParts, string $allowedPlaceholder): bool
    {
        $patternPartsArray = $routePatternParts->partsArray;

        if ($patternPartsArray === ['*']) {
            return true;
        }

        $partsArray = $this->partsArray;
        $pathParamIndex = array_search($allowedPlaceholder, $patternPartsArray);
        if ($pathParamIndex !== false) {
            $patternPartsArray[$pathParamIndex] = '';
            $partsArray[$pathParamIndex] = '';
        }

        return $patternPartsArray === $partsArray;
    }

    public function getPlaceholderValue(RouteParts $patternWithPlaceholder, string $placeholder): string
    {
        $pathParamIndex = array_search($placeholder, $patternWithPlaceholder->partsArray);
        if ($pathParamIndex === false) {
            return '';
        } else {
            return $this->partsArray[$pathParamIndex];
        }
    }
}