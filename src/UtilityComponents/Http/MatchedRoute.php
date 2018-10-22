<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.22
 * Time: 12.44
 */

namespace Edvardas\Hyphenation\UtilityComponents\Http;


class MatchedRoute
{
    private $requestRoute;
    private $pathParam = '';

    public function __construct(Route $requestRoute, RoutePattern $matchedRoutePattern)
    {
        $this->requestRoute = $requestRoute;
        $this->setPathParam($matchedRoutePattern);
    }

    public function getPathParam(): string
    {
        return $this->pathParam;
    }

    public function getQueryParams(): array
    {
        return $this->requestRoute->getQueryParams();
    }

    private function setPathParam(RoutePattern $matchedRoutePattern): void
    {
        $this->pathParam = $this->requestRoute->getRouteParts()->getCorresponding($matchedRoutePattern->getPatternParts(), '{param}');
    }
}