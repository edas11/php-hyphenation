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
    private $routeArray;
    private $pathParam;
    private $matches;

    public function __construct(string $routeString)
    {
        $this->routeArray = $route = explode('/', trim($routeString, '/'));
    }

    /**
     * @return string|null
     */
    public function pathAt(int $pathIndex)
    {
        if (array_key_exists($pathIndex, $this->routeArray)) {
            return $this->routeArray[$pathIndex];
        } else {
            return null;
        }
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
}