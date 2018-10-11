<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 16.43
 */

namespace Edvardas\Hyphenation\Hyphenator\Controller;

use Edvardas\Hyphenation\Hyphenator\Action\Action;

class HttpController implements Controller
{
    private $input;
    private $route;

    public function __construct(ConsoleInput $input)
    {
        $this->input = $input;
        $this->route = HttpRequest::getRoute();
    }

    public function getAction(): Action
    {
        $choice = $this->input->getActionInput();
        switch ($choice) {
            case InputCodes::HYPHENATE_ACTION:
                return $this->getHyphenationAction();
                break;
            case InputCodes::PUT_PATTERNS_IN_DB_ACTION:
                return new PutPatternsInDbAction($this);
                break;
            case InputCodes::BAD_REQUEST_ACTION:
                return new BadRequestAction($this);
                break;
            case InputCodes::GET_KNOWN_WORDS_ACTION:
                return new GetKnownWordsAction($this);
                break;
            case InputCodes::PUT_WORD_ACTION:
                return new PutWordAction($this);
                break;
            case InputCodes::DELETE_WORD_ACTION:
                return new DeleteWordAction($this);
        }
    }

    private function getHyphenationAction(): Action
    {
        $source = $this->input->getSourceInput();
        $this->sourceInput = $source;
        switch ($source) {
            case InputCodes::FILE_SRC:
                return new HyphenateWordsActionFile($this);
                break;
            case InputCodes::DB_SRC:
                return new HyphenateWordsActionDB($this);
                break;
        }
    }

    public function getActionInput(): int
    {
        switch (HttpRequest::getMethod()) {
            case 'GET':
                return $this->getMethodActionInput();
                break;
            case 'POST':
                return $this->postMethodActionInput();
                break;
            case 'PUT':
                return $this->putMethodActionInput();
                break;
            case 'DELETE':
                return $this->deleteMethodActionInput();
                break;
        }
    }

    public function getSourceInput(): int
    {
        return InputCodes::DB_SRC;
    }

    private function getMethodActionInput(): int
    {
        if (
            $this->route->pathAt(0) === 'hyphenation'
            && $this->route->pathAt(1) === 'words'
            && !is_null($this->route->pathAt(2))
        ) {
            $this->prepareGetWords();
            return InputCodes::GET_KNOWN_WORDS_ACTION;
        } else {
            return InputCodes::BAD_REQUEST_ACTION;
        }
    }

    private function prepareGetWords()
    {
        $this->words = (string) $this->route->pathAt(2);
    }

    private function postMethodActionInput(): int
    {
        $this->preparePostWords();
        if (
            $this->route->pathAt(0) === 'hyphenation'
            && $this->route->pathAt(1) === 'words'
            && is_null($this->route->pathAt(2))
            && $this->words !== ''
        ) {
            return InputCodes::HYPHENATE_ACTION;
        } else {
            return InputCodes::BAD_REQUEST_ACTION;
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
            $this->words = trim($wordsString);
        }
    }

    private function putMethodActionInput(): int
    {
        if (
            $this->route->pathAt(0) === 'hyphenation'
            && $this->route->pathAt(1) === 'words'
            && !is_null($this->route->pathAt(2))
        ) {
            $this->preparePutWords();
            return InputCodes::PUT_WORD_ACTION;
        } else {
            return InputCodes::BAD_REQUEST_ACTION;
        }
    }

    private function preparePutWords()
    {
        $this->words = (string) $this->route->pathAt(2);
    }

    private function deleteMethodActionInput(): int
    {
        if (
            $this->route->pathAt(0) === 'hyphenation'
            && $this->route->pathAt(1) === 'words'
            && !is_null($this->route->pathAt(2))
        ) {
            $this->prepareDeleteWords();
            return InputCodes::DELETE_WORD_ACTION;
        } else {
            return InputCodes::BAD_REQUEST_ACTION;
        }
    }

    private function prepareDeleteWords()
    {
        $this->words = (string) $this->route->pathAt(2);
    }
}