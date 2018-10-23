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
use Edvardas\Hyphenation\Hyphenator\ModelAction\WordsHyphenationWithDbModelAction;
use Edvardas\Hyphenation\Hyphenator\Controller\Controller;
use Edvardas\Hyphenation\Hyphenator\ModelInput\HyphenationInputBuilder;
use Edvardas\Hyphenation\Hyphenator\Output\WebOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HttpDataProviderFactory;
use Edvardas\Hyphenation\UtilityComponents\Http\HttpRequest;
use Edvardas\Hyphenation\UtilityComponents\Http\Router;
use Psr\Log\LoggerInterface;

class ApiPostWordsController implements Controller
{
    private $matchedRoute;
    private $modelInputBuilder;
    private $body;
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
        $this->matchedRoute = $router->getMatchedRoute();
        $this->body = $request->parseBody();
        $this->modelInputBuilder = $modelInputBuilder;
        $this->output = $output;
        $this->modelFactory = $modelFactory;
        $this->logger = $logger;
    }

    public function handleRequest(): void
    {
        $this->output->configureOutput('application/json');
        if ($this->body->hasArray('words')) {
            $lowerCaseWords = array_map(function (string $rawWord) {
                return strtolower($rawWord);
            }, array_values($this->body->get('words')) );
            $this->modelInputBuilder->setWordsInput($lowerCaseWords);
        }
        $action = new WordsHyphenationWithDbModelAction(
            $this->modelInputBuilder->build(),
            $this->output,
            $this->modelFactory,
            $this->logger
        );
        $action->execute();
    }
}