<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.17
 * Time: 11.37
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\UtilityComponents\Http;

class Router
{
    private $routeConfig;
    private $requestRoute;
    private $method;
    private $possibleRoutes;
    private $routeHandlerName;
    private $matchedRoute;

    public function __construct(HttpRequest $request)
    {
        $this->requestRoute = $request->parseRoute();
        $this->method = $request->getMethod();
        $this->routeConfig = require 'routes.php';
        $this->doParsing();
    }

    public function getRouteHandlerName(): string
    {
        return $this->routeHandlerName;
    }

    public function getMatchedRoute(): MatchedRoute
    {
        return $this->matchedRoute;
    }

    private function doParsing(): void
    {
        $this->parseHttpMethod();
        $this->parseHttpMatchedRoute();
    }

    private function parseHttpMethod(): void
    {
        switch ($this->method) {
            case 'GET':
                $this->possibleRoutes = $this->routeConfig['get'];
                break;
            case 'POST':
                $this->possibleRoutes = $this->routeConfig['post'];
                break;
            case 'PUT':
                $this->possibleRoutes = $this->routeConfig['put'];
                break;
            case 'DELETE':
                $this->possibleRoutes = $this->routeConfig['delete'];
                break;
        }
    }

    private function parseHttpMatchedRoute(): void
    {
        foreach ($this->possibleRoutes as $possibleRoute => $routeHandlerName) {
            $possibleRoute = new RoutePattern($possibleRoute);
            if ($this->requestRoute->matches($possibleRoute)) {
                $this->matchedRoute = new MatchedRoute($this->requestRoute, $possibleRoute);
                $this->routeHandlerName = $routeHandlerName;
                return;
            }
        }
        $this->routeHandlerName = '';
    }
}