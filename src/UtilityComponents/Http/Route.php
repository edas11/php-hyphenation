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
    private $routeString;
    private $routeArray;

    public function __construct(string $routeString)
    {
        //$this->routeString = $routeString;
        $this->routeArray = $route = explode('/', $routeString);
        array_shift($this->routeArray);
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
}