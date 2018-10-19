<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 16.43
 */

namespace Edvardas\Hyphenation\Hyphenator\Controller;

use Edvardas\Hyphenation\Hyphenator\Action\Action;
use Edvardas\Hyphenation\Hyphenator\Action\BadRequestAction;
use Edvardas\Hyphenation\Hyphenator\Console\HttpInput;
use Edvardas\Hyphenation\Hyphenator\Output\WebOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationHttpDataProvider;
use Edvardas\Hyphenation\Hyphenator\Providers\HttpDataProviderFactory;
use Edvardas\Hyphenation\UtilityComponents\Http\HttpRequest;
use Edvardas\Hyphenation\UtilityComponents\Http\Router;

class HttpController implements Controller
{
    private $factory;
    private $router;
    private $request;
    private $output;

    public function __construct(
        HttpDataProviderFactory $factory,
        HttpRequest $request,
        Router $router,
        WebOutput $output
    ) {
        $this->factory = $factory;
        $this->request = $request;
        $this->router = $router;
        $this->output = $output;
    }

    public function getAction(): Action
    {
        $handlerName = $this->router->getRouteHandlerName();
        $handlerName = "Edvardas\Hyphenation\Hyphenator\Controller\WebControllers\\$handlerName";
        if (!class_exists($handlerName)) {
            return new BadRequestAction($this->factory->build(), $this->output);
        }
        $appController = new $handlerName($this->factory, $this->request, $this->router, $this->output);
        if ($appController instanceof Controller) {
            return $appController->getAction();
        }
    }
}