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
use Edvardas\Hyphenation\Hyphenator\Action\HyphenatedWordsRetrievalAction;
use Edvardas\Hyphenation\Hyphenator\Controller\Controller;
use Edvardas\Hyphenation\Hyphenator\Output\WebOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HttpDataProviderFactory;
use Edvardas\Hyphenation\UtilityComponents\Http\HttpRequest;
use Edvardas\Hyphenation\UtilityComponents\Http\Router;

class ApiGetWordsController implements Controller
{
    private $matchedRoute;
    private $factory;
    private $body;
    private $output;

    public function __construct(
        HttpDataProviderFactory $factory,
        HttpRequest $request,
        Router $router,
        WebOutput $output
    ) {
        $this->matchedRoute = $router->getMatchedRoute();
        $this->body = $request->parseBody();
        $this->factory = $factory;
        $this->output = $output;
    }

    public function handleRequest(): void
    {
        $this->output->configureOutput('application/json');
        $queryParams = $this->matchedRoute->getQueryParams();
        if (array_key_exists('for', $queryParams)) {
            $this->factory->setWords([strtolower($queryParams['for'])]);
        }
        $action = new HyphenatedWordsRetrievalAction($this->factory->build(), $this->output);
        $action->execute();
    }
}