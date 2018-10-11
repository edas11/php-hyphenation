<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 16.41
 */

namespace Edvardas\Hyphenation\Hyphenator\Controller;

use Edvardas\Hyphenation\Hyphenator\Action\Action;
use Edvardas\Hyphenation\Hyphenator\Input\ConsoleInput;

class ConsoleController implements Controller
{
    private $input;

    public function __construct(ConsoleInput $input)
    {
        $this->input = $input;
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
}