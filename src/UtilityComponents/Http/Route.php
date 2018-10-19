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
    private $routeArray = [];
    private $queryString = '';

    public function __construct(string $pathString)
    {
        $queryPos = strpos($pathString, '?');
        if ($queryPos === false) {
            $routeString = $pathString;
        } else {
            $routeString = substr($pathString, 0, $queryPos);
            if (strlen($pathString) === $queryPos + 1) {
                $this->queryString = '';
            } else {
                $this->queryString = substr($pathString, $queryPos + 1);
            }
        }
        $this->routeArray = $route = explode('/', trim($routeString, '/'));
    }

    /**
     * @param string[] $match
     */
    public function match(Route $routeToMatch): MatchedRoute
    {
        $match = $routeToMatch->routeArray;
        if (count($this->routeArray) !== count($match)) {
            return new MatchedRoute(false);
        }
        foreach ($match as $pathIndex => $matchString) {
            $pathParam = '';

            if (!array_key_exists($pathIndex, $this->routeArray)) {
                return new MatchedRoute(false);
            }
            if ($matchString === '{param}') {
                $pathParam = $this->routeArray[$pathIndex];
                continue;
            }
            if ($this->routeArray[$pathIndex] !== $matchString) {
                return new MatchedRoute(false);
            }
        }
        $queryParams = [];
        parse_str($this->queryString, $queryParams);
        return new MatchedRoute(true, $pathParam, $queryParams);
    }
}