<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 16.41
 */
declare(strict_types=1);

namespace Edvardas\Hyphenation\Hyphenator\Controller;

use Edvardas\Hyphenation\Hyphenator\Action\Action;
use Edvardas\Hyphenation\Hyphenator\Action\WordsHyphenationWithDbAction;
use Edvardas\Hyphenation\Hyphenator\Action\WordsHyphenationAction;
use Edvardas\Hyphenation\Hyphenator\Action\PatternsSaveInDbAction;
use Edvardas\Hyphenation\Hyphenator\Console\InputCodes;
use Edvardas\Hyphenation\Hyphenator\Console\InputDialog;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationConsoleDataProvider;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationConsoleDataProviderFactory;

class ConsoleController implements Controller
{
    private $provider;
    private $input;

    public function __construct(InputDialog $input, HyphenationConsoleDataProviderFactory $factory)
    {
        $this->input = $input;
        $this->provider = $factory->build();
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