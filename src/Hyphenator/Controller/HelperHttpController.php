<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.17
 * Time: 11.41
 */
/*declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Controller;

use Edvardas\Hyphenation\Hyphenator\Action\Action;
use Edvardas\Hyphenation\Hyphenator\Action\NullAction;
use Edvardas\Hyphenation\Hyphenator\Action\PageGetAction;
use Edvardas\Hyphenation\Hyphenator\Action\WordDeleteAction;
use Edvardas\Hyphenation\Hyphenator\Action\WordPutAction;
use Edvardas\Hyphenation\Hyphenator\Action\WordsGetKnownAction;
use Edvardas\Hyphenation\Hyphenator\Action\WordsHyphenationWithDbAction;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;
use Edvardas\Hyphenation\Hyphenator\Providers\HttpDataProviderFactory;
use Edvardas\Hyphenation\UtilityComponents\Http\HttpRequest;
use Edvardas\Hyphenation\UtilityComponents\Http\Router;

class HelperHttpController
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

    public function getWords(): Action
    {
        $this->factory->configureWebOutput('application/json');
        $queryParams = $this->matchedRoute->getQueryParams();
        if (array_key_exists('for', $queryParams)) {
            $this->factory->setWords([$queryParams['for']]);
        }
        return new WordsGetKnownAction($this->factory->build());
    }

    public function postWords(): Action
    {
        $this->factory->configureWebOutput('application/json');
        if ($this->body->hasArray('words')) {
            $this->factory->setWords(array_values($this->body->get('words')));
        }
        return new WordsHyphenationWithDbAction($this->factory->build());
    }

    public function putWords(): Action
    {
        $this->factory->configureWebOutput('application/json');
        if ($this->body->hasString('newHyphenatedWord')) {
            $this->factory->setHyphenatedWords([$this->body->get('newHyphenatedWord')]);
        }
        $this->factory->setWords([$this->matchedRoute->getPathParam()]);
        return new WordPutAction($this->factory->build());
    }

    public function deleteWords(): Action
    {
        $this->factory->configureWebOutput('application/json');
        $this->factory->setWords([$this->matchedRoute->getPathParam()]);
        return new WordDeleteAction($this->factory->build());
    }

    public function getPage(): Action
    {
        $this->factory->configureWebOutput('text/html', 'pages/page.php');
        return new NullAction();
    }

    public function getWordsPage(): Action
    {
        $this->factory->configureWebOutput('text/html', 'pages/showWordsPage.php');
        return new NullAction();
    }

    public function getPatternsPage(): Action
    {
        $this->factory->configureWebOutput('text/html', 'pages/showPatternsPage.php');
        return new NullAction();
    }

    public function hyphenateWordsPage(): Action
    {
        $this->factory->configureWebOutput('text/html', 'pages/hyphenateWordsPage.php');
        return new NullAction();
    }

    public function changeHyphenationPage(): Action
    {
        $this->factory->configureWebOutput('text/html', 'pages/changeHyphenationPage.php');
        return new NullAction();
    }
}*/