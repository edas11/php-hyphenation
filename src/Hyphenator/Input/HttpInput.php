<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 10.31
 */

namespace Edvardas\Hyphenation\Hyphenator\Input;

use Edvardas\Hyphenation\UtilityComponents\Http\HttpRequest;
use Edvardas\Hyphenation\UtilityComponents\Http\Route;

class HttpInput implements HyphenationInput
{
    /**
     * @var Route
     */
    private $route;
    private $words = '';

    public function __construct()
    {
        $this->route = HttpRequest::getRoute();
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

    public function getWordsInput(): string
    {
        return $this->words;
    }

    public function getAlgorithmInput(): int
    {
        return InputCodes::FULL_TREE_ALGORITHM;
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

    public function getHyphenatedWordsInput(): string
    {
        $body = HttpRequest::getBody();
        if (!array_key_exists('hyphenatedWord', $body) || !is_string($body['hyphenatedWord'])) {
            return '';
        }
        return $body['hyphenatedWord'];
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