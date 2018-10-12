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
use Edvardas\Hyphenation\Hyphenator\Action\HyphenateWordsActionDB;
use Edvardas\Hyphenation\Hyphenator\Action\HyphenateWordsActionFile;
use Edvardas\Hyphenation\Hyphenator\Action\PutPatternsInDbAction;
use Edvardas\Hyphenation\Hyphenator\Action\PutWordAction;
use Edvardas\Hyphenation\Hyphenator\Input\HttpInput;
use Edvardas\Hyphenation\Hyphenator\Output\HyphenationOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationHttpDataProvider;
use Edvardas\Hyphenation\UtilityComponents\Http\HttpRequest;

class HttpController implements Controller
{
    private $provider;
    private $input;
    private $route;

    public function __construct(HyphenationOutput $output)
    {
        $this->input = new HttpInput();
        $this->provider = new HyphenationHttpDataProvider($this->input, $output);
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
        if (
            $this->route->pathAt(0) === 'hyphenation'
            && $this->route->pathAt(1) === 'words'
            && !is_null($this->route->pathAt(2))
        ) {
            $this->prepareGetWords();
            return new GetKnownWordsAction($this->provider);
        } else {
            return new BadRequestAction($this->provider);
        }
    }

    private function prepareGetWords()
    {
        $this->provider->setWordsInput((string) $this->route->pathAt(2));
    }

    private function postMethodAction(): Action
    {
        $this->preparePostWords();
        if (
            $this->route->pathAt(0) === 'hyphenation'
            && $this->route->pathAt(1) === 'words'
            && is_null($this->route->pathAt(2))
        ) {
            return new HyphenateWordsActionDB($this->provider);
        } else {
            return new BadRequestAction($this->provider);
        }
    }

    private function preparePostWords()
    {
        $body = HttpRequest::getBody();
        if (!array_key_exists('words', $body) || !is_array($body['words'])) {
            return;
        } else {
            $wordsString = '';
            foreach ($body['words'] as $word) {
                $wordsString = $wordsString . " $word";
            }
            $this->provider->setWordsInput(trim($wordsString));
        }
    }

    private function putMethodAction(): Action
    {
        if (
            $this->route->pathAt(0) === 'hyphenation'
            && $this->route->pathAt(1) === 'words'
            && !is_null($this->route->pathAt(2))
        ) {
            $this->preparePutWords();
            return new PutWordAction($this->provider);
        } else {
            return new BadRequestAction($this->provider);
        }
    }

    private function preparePutWords()
    {
        $this->provider->setWordsInput((string) $this->route->pathAt(2));
    }

    private function deleteMethodAction(): Action
    {
        if (
            $this->route->pathAt(0) === 'hyphenation'
            && $this->route->pathAt(1) === 'words'
            && !is_null($this->route->pathAt(2))
        ) {
            $this->prepareDeleteWords();
            return new DeleteWordAction($this->provider);
        } else {
            return new BadRequestAction($this->provider);
        }
    }

    private function prepareDeleteWords()
    {
        $this->provider->setWordsInput((string) $this->route->pathAt(2));
    }
}