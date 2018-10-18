<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.17
 * Time: 11.41
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Controller\WebControllers;

use Edvardas\Hyphenation\Hyphenator\Action\Action;
use Edvardas\Hyphenation\Hyphenator\Action\NullAction;
use Edvardas\Hyphenation\Hyphenator\Controller\Controller;
use Edvardas\Hyphenation\Hyphenator\Providers\HttpDataProviderFactory;
use Edvardas\Hyphenation\UtilityComponents\Http\HttpRequest;
use Edvardas\Hyphenation\UtilityComponents\Http\Router;

class PageMainController implements Controller
{
    private $matchedRoute;
    private $factory;
    private $body;

    public function __construct(HttpDataProviderFactory $factory, HttpRequest $request, Router $router)
    {
        $this->matchedRoute = $router->getMatchedRoute();
        $this->body = $request->parseBody();
        $this->factory = $factory;
    }

    public function getAction(): Action
    {
        $this->factory->configureWebOutput('text/html', 'pages/main-page.php');
        return new NullAction();
    }
}