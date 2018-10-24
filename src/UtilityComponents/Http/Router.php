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
    private $routePatternsForCurrentMethod;
    private $routeHandlerName = '';
    private $matchedRoute;

    public function __construct(HttpRequest $request, array $routeConfig)
    {
        $this->requestRoute = $request->parseRoute();
        $this->method = $request->getMethod();
        $this->routeConfig = $routeConfig;
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
        $this->determineRoutePatternsForCurrentMethod();
        $this->parseHttpMatchedRoute();
    }

    private function determineRoutePatternsForCurrentMethod(): void
    {
        switch ($this->method) {
            case 'GET':
                $this->routePatternsForCurrentMethod = $this->routeConfig['get'];
                break;
            case 'POST':
                $this->routePatternsForCurrentMethod = $this->routeConfig['post'];
                break;
            case 'PUT':
                $this->routePatternsForCurrentMethod = $this->routeConfig['put'];
                break;
            case 'DELETE':
                $this->routePatternsForCurrentMethod = $this->routeConfig['delete'];
                break;
        }
    }

    private function parseHttpMatchedRoute(): void
    {
        foreach ($this->routePatternsForCurrentMethod as $routePatternString => $routeHandlerName) {
            $routePattern = new RoutePattern($routePatternString);
            if ($this->requestRoute->matches($routePattern)) {
                $this->matchedRoute = new MatchedRoute($this->requestRoute, $routePattern);
                $this->routeHandlerName = $routeHandlerName;
                return;
            }
        }
    }
}