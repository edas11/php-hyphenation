<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.17
 * Time: 11.41
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Controller\WebControllers;

use Edvardas\Hyphenation\Hyphenator\Model\ModelFactory;
use Edvardas\Hyphenation\Hyphenator\ModelAction\ModelAction;
use Edvardas\Hyphenation\Hyphenator\ModelAction\HyphenatedWordDeletionModelAction;
use Edvardas\Hyphenation\Hyphenator\Controller\Controller;
use Edvardas\Hyphenation\Hyphenator\ModelInput\HyphenationInputBuilder;
use Edvardas\Hyphenation\Hyphenator\Output\BufferedOutput;
use Edvardas\Hyphenation\Hyphenator\Output\WebOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HttpDataProviderFactory;
use Edvardas\Hyphenation\UtilityComponents\Http\HttpRequest;
use Edvardas\Hyphenation\UtilityComponents\Http\Router;
use Psr\Log\LoggerInterface;

class ApiDeleteWordsController implements Controller
{
    private $matchedRoute;
    private $modelInputBuilder;
    private $body;
    private $output;
    private $modelFactory;

    public function __construct(
        HyphenationInputBuilder $modelInputBuilder,
        HttpRequest $request,
        Router $router,
        WebOutput $output,
        ModelFactory $modelFactory,
        LoggerInterface $logger
    ) {
        $this->matchedRoute = $router->getMatchedRoute();
        $this->body = $request->parseBody();
        $this->modelInputBuilder = $modelInputBuilder;
        $this->output = $output;
        $this->modelFactory = $modelFactory;
    }

    public function handleRequest(): void
    {
        $this->output->configureOutput('application/json');
        $this->modelInputBuilder->setWordsInput([strtolower($this->matchedRoute->getPathParam())]);
        $action = new HyphenatedWordDeletionModelAction(
            $this->modelInputBuilder->build(),
            $this->output,
            $this->modelFactory
        );
        $action->execute();
    }
}