<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 16.41
 */

namespace Edvardas\Hyphenation\Hyphenator\Controller;

use Edvardas\Hyphenation\App\App;
use Edvardas\Hyphenation\Hyphenator\Action\Action;
use Edvardas\Hyphenation\Hyphenator\Action\BadRequestAction;
use Edvardas\Hyphenation\Hyphenator\Action\DeleteWordAction;
use Edvardas\Hyphenation\Hyphenator\Action\GetKnownWordsAction;
use Edvardas\Hyphenation\Hyphenator\Action\HyphenateWordsActionDB;
use Edvardas\Hyphenation\Hyphenator\Action\HyphenateWordsActionFile;
use Edvardas\Hyphenation\Hyphenator\Action\PutPatternsInDbAction;
use Edvardas\Hyphenation\Hyphenator\Action\PutWordAction;
use Edvardas\Hyphenation\Hyphenator\Algorithm\FullTreeHyphenationAlgorithm;
use Edvardas\Hyphenation\Hyphenator\Algorithm\HyphenationAlgorithmInterface;
use Edvardas\Hyphenation\Hyphenator\Algorithm\ShortTreeHyphenationAlgorithm;
use Edvardas\Hyphenation\Hyphenator\Input\ConsoleInput;
use Edvardas\Hyphenation\Hyphenator\Input\InputCodes;
use Edvardas\Hyphenation\Hyphenator\Model\Patterns;
use Edvardas\Hyphenation\Hyphenator\Output\HyphenationOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationConsoleDataProvider;

class ConsoleController implements Controller
{
    private $provider;
    private $input;

    public function __construct(HyphenationOutput $output)
    {
        $this->input = new ConsoleInput();
        $this->provider = new HyphenationConsoleDataProvider($this->input, $output);
    }

    public function getAction(): Action
    {
        $choice = $this->input->getActionInput();
        switch ($choice) {
            case InputCodes::HYPHENATE_ACTION:
                return $this->getHyphenationAction();
                break;
            case InputCodes::PUT_PATTERNS_IN_DB_ACTION:
                return new PutPatternsInDbAction($this->provider);
                break;
            case InputCodes::BAD_REQUEST_ACTION:
                return new BadRequestAction($this->provider);
                break;
            case InputCodes::GET_KNOWN_WORDS_ACTION:
                return new GetKnownWordsAction($this->provider);
                break;
            case InputCodes::PUT_WORD_ACTION:
                return new PutWordAction($this->provider);
                break;
            case InputCodes::DELETE_WORD_ACTION:
                return new DeleteWordAction($this->provider);
        }
    }

    private function getHyphenationAction(): Action
    {
        $source = $this->input->getSourceInput();
        $this->sourceInput = $source;
        switch ($source) {
            case InputCodes::FILE_SRC:
                return new HyphenateWordsActionFile($this->provider);
                break;
            case InputCodes::DB_SRC:
                return new HyphenateWordsActionDB($this->provider);
                break;
        }
    }
}