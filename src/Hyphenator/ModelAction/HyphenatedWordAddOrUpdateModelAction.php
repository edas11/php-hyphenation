<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 14.14
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\ModelAction;

use Edvardas\Hyphenation\Hyphenator\Model\ModelFactory;
use Edvardas\Hyphenation\Hyphenator\Model\HyphenatedWords;
use Edvardas\Hyphenation\Hyphenator\ModelInput\HyphenationInput;
use Edvardas\Hyphenation\Hyphenator\Output\BufferedOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;

class HyphenatedWordAddOrUpdateModelAction implements ModelAction
{
    private $output;
    private $modelFactory;
    private $inputWords;
    private $inputHyphenatedWords;

    public function __construct(
        HyphenationInput $modelInput,
        BufferedOutput $output,
        ModelFactory $modelFactory
    ) {
        $this->output = $output;
        $this->modelFactory = $modelFactory;
        $this->inputWords = $modelInput->getWordsInput();
        $this->inputHyphenatedWords = $modelInput->getHyphenatedWordsInput();
    }

    public function execute(): void
    {
        if (count($this->inputWords) < 1 || count($this->inputHyphenatedWords) < 1) {
            $this->output->set('result', ['Error']);
            return;
        }
        $word = $this->inputWords[0];
        $hyphenatedWord = $this->inputHyphenatedWords[0];
        $this->modelFactory->createHyphenatedWords([$word => $hyphenatedWord])->persist();
        $this->output->set('result', ['Success']);
    }
}