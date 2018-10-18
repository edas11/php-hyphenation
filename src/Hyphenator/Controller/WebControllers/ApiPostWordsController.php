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
use Edvardas\Hyphenation\Hyphenator\Action\WordsHyphenationWithDbAction;
use Edvardas\Hyphenation\Hyphenator\Controller\Controller;
use Edvardas\Hyphenation\Hyphenator\Providers\HttpDataProviderFactory;
use Edvardas\Hyphenation\UtilityComponents\Http\HttpRequest;
use Edvardas\Hyphenation\UtilityComponents\Http\Router;

class ApiPostWordsController implements Controller
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
        $this->factory->configureWebOutput('application/json');
        if ($this->body->hasArray('words')) {
            $this->factory->setWords(array_values($this->body->get('words')));
        }
        return new WordsHyphenationWithDbAction($this->factory->build());
    }
}