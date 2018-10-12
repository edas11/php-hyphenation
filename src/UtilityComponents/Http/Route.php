<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 12.19
 */

namespace Edvardas\Hyphenation\UtilityComponents\Http;


class Route
{
    private $routeArray = [];
    private $queryParams = [];
    private $pathParam;
    private $matches;

    public function __construct(string $pathString)
    {
        $queryPos = strpos($pathString, '?');
        if ($queryPos === false) {
            $routeString = $pathString;
        } else {
            $routeString = substr($pathString, 0, $queryPos);
            if (strlen($pathString) === $queryPos + 1) {
                $queryString = '';
            } else {
                $queryString = substr($pathString, $queryPos + 1);
            }
            $this->parseQueryParams($queryString);
        }
        $this->routeArray = $route = explode('/', trim($routeString, '/'));
    }

    /**
     * @param string[] $match
     */
    public function match(array $match): void
    {
        if (count($this->routeArray) !== count($match)) {
            $this->matches = false;
            return;
        }
        foreach ($match as $pathIndex => $matchString) {
            if (!array_key_exists($pathIndex, $this->routeArray)) {
                $this->matches = false;
                return;
            }
            if ($matchString === '{param}') {
                $this->pathParam = $this->routeArray[$pathIndex];
                continue;
            }
            if ($this->routeArray[$pathIndex] !== $matchString) {
                $this->matches = false;
                return;
            }
        }
        $this->matches = true;
    }

    public function matches(): bool
    {
        return $this->matches;
    }

    public function getPathParam(): string
    {
        return $this->pathParam;
    }

    public function getQueryParams()
    {
        return $this->queryParams;
    }

    private function parseQueryParams(string $queryString): void
    {
        $queryClauses = explode('&', $queryString);
        if (count($queryClauses) > 0) {
            foreach ($queryClauses as $clause) {
                $paramAndValueArray = explode('=', $clause);
                if (count($paramAndValueArray) !== 2) {
                    continue;
                }
                $param = $paramAndValueArray[0];
                $value = $paramAndValueArray[1];
                $this->queryParams[$param] = $value;
            }
        }
    }
}