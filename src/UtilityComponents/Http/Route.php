<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 12.19
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\UtilityComponents\Http;

class Route
{
    private $routeParts;
    private $queryParams = [];

    public function __construct(string $pathString)
    {
        $routeString = $this->extractRouteString($pathString);
        $queryString = $this->extractQueryString($pathString);
        $this->prepareQueryParameters($queryString);
        $this->prepareRouteParameters($routeString);
    }

    public function getRouteParts(): RouteParts
    {
        return $this->routeParts;
    }

    public function getQueryParams()
    {
        return $this->queryParams;
    }

    public function matches(RoutePattern $routePattern): bool
    {
        $routePatternParts = $routePattern->getPatternParts();
        return $this->routeParts->matches($routePatternParts, $routePattern->getPathParamPlaceholder());
    }

    private function prepareQueryParameters(string $queryString): void
    {
        $queryParams = [];
        parse_str($queryString, $queryParams);
        $this->queryParams = $queryParams;
    }

    private function prepareRouteParameters($routeString): void
    {
        $this->routeParts = new RouteParts($routeString);
    }

    private function extractRouteString(string $pathString): string
    {
        $path = explode('?', $pathString, 2);
        return $path[0];
    }

    private function extractQueryString(string $pathString): string
    {
        $path = explode('?', $pathString, 2);
        return count($path) === 2 ? $path[1] : '';
    }
}