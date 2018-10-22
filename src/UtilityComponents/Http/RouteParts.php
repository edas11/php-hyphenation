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
    private $routeParts;

    public function __construct(string $routeString)
    {
        $this->routeParts = explode('/', trim($routeString, '/'));
    }

    public function matches(RouteParts $routePatternParts): bool
    {
        if (count($this->routeParts) !== count($routePatternParts->routeParts)) {
            return false;
        }
        foreach ($routePatternParts->routeParts as $patternPartIndex => $patternPart) {
            if ($patternPart === '{param}') {
                continue;
            }
            if ($this->routeParts[$patternPartIndex] !== $patternPart) {
                return false;
            }
        }
        return true;
    }

    public function getCorresponding(RouteParts $parts, string $key): string
    {
        $pathParamIndex = array_search($key, $parts->routeParts);
        return $this->routeParts[$pathParamIndex];
    }
}