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
use Edvardas\Hyphenation\Hyphenator\ModelAction\HyphenatedWordAddOrUpdateModelAction;
use Edvardas\Hyphenation\Hyphenator\Controller\Controller;
use Edvardas\Hyphenation\Hyphenator\ModelInput\HyphenationInputBuilder;
use Edvardas\Hyphenation\Hyphenator\Output\WebOutput;
use Edvardas\Hyphenation\UtilityComponents\Http\HttpRequest;
use Edvardas\Hyphenation\UtilityComponents\Http\Router;
use Psr\Log\LoggerInterface;

class ApiPutWordsController implements Controller
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
        if ($this->body->hasString('newHyphenatedWord')) {
            $this->modelInputBuilder->setHyphenatedWordsInput([strtolower($this->body->get('newHyphenatedWord'))]);
        }
        $this->modelInputBuilder->setWordsInput([strtolower($this->matchedRoute->getPathParam())]);
        $action = new HyphenatedWordAddOrUpdateModelAction(
            $this->modelInputBuilder->build(),
            $this->output,
            $this->modelFactory
        );
        $action->execute();
    }
}