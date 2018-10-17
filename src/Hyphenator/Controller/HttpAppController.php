<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.17
 * Time: 11.41
 */

namespace Edvardas\Hyphenation\Hyphenator\Controller;


use Edvardas\Hyphenation\Hyphenator\Action\Action;
use Edvardas\Hyphenation\Hyphenator\Action\WordDeleteAction;
use Edvardas\Hyphenation\Hyphenator\Action\WordPutAction;
use Edvardas\Hyphenation\Hyphenator\Action\WordsGetKnownAction;
use Edvardas\Hyphenation\Hyphenator\Action\WordsHyphenationWithDbAction;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;
use Edvardas\Hyphenation\UtilityComponents\Http\MatchedRoute;

class HttpAppController
{
    private $route;
    private $provider;
    private $body;

    public function __construct(HyphenationDataProvider $provider, MatchedRoute $route, array $body)
    {
        $this->route = $route;
        $this->body = $body;
        $this->provider = $provider;
    }

    public function getWords(): Action
    {
        $queryParams = $this->route->getQueryParams();
        if (array_key_exists('for', $queryParams)) {
            $this->provider->setWords([$queryParams['for']]);
        }
        return new WordsGetKnownAction($this->provider);
    }

    public function postWords(): Action
    {
        if (array_key_exists('words', $this->body) && is_array($this->body['words'])) {
            $this->provider->setWords(array_values($this->body['words']));
        }
        return new WordsHyphenationWithDbAction($this->provider);
    }

    public function putWords(): Action
    {
        if (array_key_exists('newHyphenatedWord', $this->body) && is_string($this->body['newHyphenatedWord'])) {
            $this->provider->setHyphenatedWords([$this->body['newHyphenatedWord']]);
        }
        $this->provider->setWords([$this->route->getPathParam()]);
        return new WordPutAction($this->provider);
    }

    public function deleteWords(): Action
    {
        $this->provider->setWords([$this->route->getPathParam()]);
        return new WordDeleteAction($this->provider);
    }
}