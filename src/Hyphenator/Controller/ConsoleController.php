<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 16.41
 */

namespace Edvardas\Hyphenation\Hyphenator\Controller;

use Edvardas\Hyphenation\Hyphenator\Action\Action;
use Edvardas\Hyphenation\Hyphenator\Action\WordsHyphenationWithDbAction;
use Edvardas\Hyphenation\Hyphenator\Action\WordsHyphenationAction;
use Edvardas\Hyphenation\Hyphenator\Action\PatternsSaveInDbAction;
use Edvardas\Hyphenation\Hyphenator\Input\ConsoleInput;
use Edvardas\Hyphenation\Hyphenator\Input\InputCodes;
use Edvardas\Hyphenation\Hyphenator\Output\HyphenationOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationConsoleDataProvider;

class ConsoleController implements Controller
{
    private $provider;
    private $input;

    public function __construct(ConsoleInput $input, HyphenationConsoleDataProvider $provider)
    {
        $this->input = $input;
        $this->provider = $provider;
    }

    public function getAction(): Action
    {
        $choice = $this->input->getActionInput();
        switch ($choice) {
            case InputCodes::HYPHENATE_ACTION:
                return $this->getHyphenationAction();
                break;
            case InputCodes::PUT_PATTERNS_IN_DB_ACTION:
                return new PatternsSaveInDbAction($this->provider);
                break;
        }
    }

    private function getHyphenationAction(): Action
    {
        $source = $this->input->getSourceInput();
        $this->sourceInput = $source;
        switch ($source) {
            case InputCodes::FILE_SRC:
                return new WordsHyphenationAction($this->provider);
                break;
            case InputCodes::DB_SRC:
                return new WordsHyphenationWithDbAction($this->provider);
                break;
        }
    }
}