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
use Edvardas\Hyphenation\Hyphenator\Action\DeleteWordAction;
use Edvardas\Hyphenation\Hyphenator\Action\GetKnownWordsAction;
use Edvardas\Hyphenation\Hyphenator\Action\ComplexHyphenateWordsAction;
use Edvardas\Hyphenation\Hyphenator\Action\PutWordAction;
use Edvardas\Hyphenation\Hyphenator\Input\HttpInput;
use Edvardas\Hyphenation\Hyphenator\Output\HyphenationOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationHttpDataProvider;
use Edvardas\Hyphenation\UtilityComponents\Http\HttpRequest;

class HttpController implements Controller
{
    private $provider;
    private $route;

    public function __construct(HyphenationOutput $output)
    {
        $this->provider = new HyphenationHttpDataProvider($output);
        $this->route = HttpRequest::getRoute();
    }

    public function getAction(): Action
    {
        switch (HttpRequest::getMethod()) {
            case 'GET':
                return $this->getMethodAction();
                break;
            case 'POST':
                return $this->postMethodAction();
                break;
            case 'PUT':
                return $this->putMethodAction();
                break;
            case 'DELETE':
                return $this->deleteMethodAction();
                break;
        }
    }

    private function getMethodAction(): Action
    {
        $this->route->match(['hyphenation', 'words']);
        if ($this->route->matches()) {
            $queryParams = $this->route->getQueryParams();
            if (array_key_exists('for', $queryParams)) {
                $this->provider->setWords([$queryParams['for']]);
            }
            return new GetKnownWordsAction($this->provider);
        } else {
            return new BadRequestAction($this->provider);
        }
    }

    private function postMethodAction(): Action
    {
        $this->route->match(['hyphenation', 'words']);
        if ($this->route->matches()) {

            $body = HttpRequest::getBody();
            if (array_key_exists('words', $body) && is_array($body['words'])) {
                $this->provider->setWords(array_values($body['words']));
            }

            return new ComplexHyphenateWordsAction($this->provider);
        } else {
            return new BadRequestAction($this->provider);
        }
    }

    private function putMethodAction(): Action
    {
        $this->route->match(['hyphenation', 'words', '{param}']);
        if ($this->route->matches()) {

            $body = HttpRequest::getBody();
            if (array_key_exists('newHyphenatedWord', $body) && is_string($body['newHyphenatedWord'])) {
                $this->provider->setHyphenatedWords([$body['newHyphenatedWord']]);
            }
            $this->provider->setWords([$this->route->getPathParam()]);

            return new PutWordAction($this->provider);
        } else {
            return new BadRequestAction($this->provider);
        }
    }

    private function deleteMethodAction(): Action
    {
        $this->route->match(['hyphenation', 'words', '{param}']);
        if ($this->route->matches()) {
            $this->provider->setWords([$this->route->getPathParam()]);
            return new DeleteWordAction($this->provider);
        } else {
            return new BadRequestAction($this->provider);
        }
    }
}