<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 16.43
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Controller;

use Edvardas\Hyphenation\Hyphenator\Console\HttpInput;
use Edvardas\Hyphenation\Hyphenator\Model\ModelFactory;
use Edvardas\Hyphenation\Hyphenator\ModelInput\HyphenationInputBuilder;
use Edvardas\Hyphenation\Hyphenator\Output\WebOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationHttpDataProvider;
use Edvardas\Hyphenation\UtilityComponents\Http\HttpRequest;
use Edvardas\Hyphenation\UtilityComponents\Http\Router;
use Psr\Log\LoggerInterface;

class HttpController implements Controller
{
    private $modelInputBuilder;
    private $router;
    private $request;
    private $output;
    private $modelFactory;
    private $logger;

    public function __construct(
        HyphenationInputBuilder $modelInputBuilder,
        HttpRequest $request,
        Router $router,
        WebOutput $output,
        ModelFactory $modelFactory,
        LoggerInterface $logger
    ) {
        $this->modelInputBuilder = $modelInputBuilder;
        $this->request = $request;
        $this->router = $router;
        $this->output = $output;
        $this->modelFactory = $modelFactory;
        $this->logger = $logger;
    }

    public function handleRequest(): void
    {
        $handlerName = $this->getHandlerNameForCurrentRoute();
        if (class_exists($handlerName) && method_exists($handlerName, 'handleRequest')) {
            $appController = new $handlerName(
                $this->modelInputBuilder,
                $this->request,
                $this->router,
                $this->output,
                $this->modelFactory,
                $this->logger
            );
            $appController->handleRequest();
        } else {
            $this->errorResponse();
        }
    }

    private function getHandlerNameForCurrentRoute(): string
    {
        $handlerName = $this->router->getRouteHandlerName();
        $handlerName = "Edvardas\Hyphenation\Hyphenator\Controller\WebControllers\\$handlerName";
        return $handlerName;
    }

    private function errorResponse(): void
    {
        http_response_code(400);
        $this->output->set('error', 'Bad request');
    }
}