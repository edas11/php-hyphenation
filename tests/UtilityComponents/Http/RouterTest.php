<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.23
 * Time: 12.07
 */

namespace UtilityComponents\Http;

use Edvardas\Hyphenation\UtilityComponents\Http\HttpRequest;
use Edvardas\Hyphenation\UtilityComponents\Http\Route;
use Edvardas\Hyphenation\UtilityComponents\Http\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    private $routeConfig;
    private $httpRequest;

    public function setUp()
    {
        $this->httpRequest = $this->getMockBuilder(HttpRequest::class)
            ->disableOriginalConstructor()
            ->setMethods(['getMethod', 'parseRoute'])
            ->getMock();
        $this->routeConfig = [
            'get' => [
                'route/to/somewhere/'  => 'getHandler'
            ],
            'post' => [
                'route/to/somewhere/'  => 'postHandler'
            ],
            'put' => [
                'route/to/somewhere/{param}'  => 'putHandler'
            ],
            'delete' => [
                'route/to/somewhere/{param}'  => 'deleteHandler'
            ]
        ];
    }

    public function testGetMethod()
    {
        $this->httpRequest->method('parseRoute')
            ->willReturn(new Route('route/to/somewhere?for=thing'));
        $this->httpRequest->method('getMethod')
            ->willReturn('GET');
        $router = new Router($this->httpRequest, $this->routeConfig);
        $matchedRoute = $router->getMatchedRoute();
        $handlerName = $router->getRouteHandlerName();
        $this->assertSame('getHandler', $handlerName, 'Handler are not same');
        $this->assertSame(['for' => 'thing'], $matchedRoute->getQueryParams(), 'Query params are not same');
        $this->assertSame('', $matchedRoute->getPathParam(), 'Path params are not same');
    }

    public function testPostMethod()
    {
        $this->httpRequest->method('parseRoute')
            ->willReturn(new Route('route/to/somewhere'));
        $this->httpRequest->method('getMethod')
            ->willReturn('POST');
        $router = new Router($this->httpRequest, $this->routeConfig);
        $matchedRoute = $router->getMatchedRoute();
        $handlerName = $router->getRouteHandlerName();
        $this->assertSame('postHandler', $handlerName, 'Handler are not same');
        $this->assertSame([], $matchedRoute->getQueryParams(), 'Query params are not same');
        $this->assertSame('', $matchedRoute->getPathParam(), 'Path params are not same');
    }

    public function testPutMethod()
    {
        $this->httpRequest->method('parseRoute')
            ->willReturn(new Route('route/to/somewhere/param'));
        $this->httpRequest->method('getMethod')
            ->willReturn('PUT');
        $router = new Router($this->httpRequest, $this->routeConfig);
        $matchedRoute = $router->getMatchedRoute();
        $handlerName = $router->getRouteHandlerName();
        $this->assertSame('putHandler', $handlerName, 'Handler are not same');
        $this->assertSame([], $matchedRoute->getQueryParams(), 'Query params are not same');
        $this->assertSame('param', $matchedRoute->getPathParam(), 'Path params are not same');
    }

    public function testDeleteMethod()
    {
        $this->httpRequest->method('parseRoute')
            ->willReturn(new Route('route/to/somewhere/param'));
        $this->httpRequest->method('getMethod')
            ->willReturn('DELETE');
        $router = new Router($this->httpRequest, $this->routeConfig);
        $matchedRoute = $router->getMatchedRoute();
        $handlerName = $router->getRouteHandlerName();
        $this->assertSame('deleteHandler', $handlerName, 'Handler are not same');
        $this->assertSame([], $matchedRoute->getQueryParams(), 'Query params are not same');
        $this->assertSame('param', $matchedRoute->getPathParam(), 'Path params are not same');
    }
}
