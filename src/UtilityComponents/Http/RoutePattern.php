<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.22
 * Time: 12.53
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\UtilityComponents\Http;

class RoutePattern
{
    private $routePatternParts = [];
    private $pathParamIndex = 0;

    public function __construct(string $patternString)
    {
        $this->routePatternParts = new RouteParts($patternString);
    }

    public function getPatternParts(): RouteParts
    {
        return $this->routePatternParts;
    }

    public function getPathParamIndex(): int
    {
        return $this->pathParamIndex;
    }
}