<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.17
 * Time: 11.41
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Controller\WebControllers;

use Edvardas\Hyphenation\Hyphenator\Action\HyphenationAction;
use Edvardas\Hyphenation\Hyphenator\Action\WordPutHyphenationAction;
use Edvardas\Hyphenation\Hyphenator\Controller\Controller;
use Edvardas\Hyphenation\Hyphenator\Output\WebOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HttpDataProviderFactory;
use Edvardas\Hyphenation\UtilityComponents\Http\HttpRequest;
use Edvardas\Hyphenation\UtilityComponents\Http\Router;

class ApiPutWordsController implements Controller
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

    public function getAction(): HyphenationAction
    {
        $this->output->configureOutput('application/json');
        if ($this->body->hasString('newHyphenatedWord')) {
            $this->factory->setHyphenatedWords([strtolower($this->body->get('newHyphenatedWord'))]);
        }
        $this->factory->setWords([strtolower($this->matchedRoute->getPathParam())]);
        return new WordPutHyphenationAction($this->factory->build(), $this->output);
    }
}