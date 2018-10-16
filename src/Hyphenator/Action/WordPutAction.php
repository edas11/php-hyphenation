<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 14.14
 */

namespace Edvardas\Hyphenation\Hyphenator\Action;


use Edvardas\Hyphenation\Hyphenator\Model\ModelFactory;
use Edvardas\Hyphenation\Hyphenator\Model\HyphenatedWords;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;

class WordPutAction implements Action
{
    private $output;
    private $modelFactory;
    private $inputWords;
    private $inputHyphenatedWords;

    public function __construct(HyphenationDataProvider $dataProvider)
    {
        $this->output = $dataProvider->getOutput();
        $this->modelFactory = $dataProvider->getModelFactory();
        $this->inputWords = $dataProvider->getWordsInput();
        $this->inputHyphenatedWords = $dataProvider->getHyphenatedWordsInput();
    }

    public function execute(): void
    {
        if (count($this->inputWords) < 1 || count($this->inputHyphenatedWords) < 1) {
            $this->output->printResult(['Error']);
            return;
        }
        $word = $this->inputWords[0];
        $hyphenatedWord = $this->inputHyphenatedWords[0];
        $this->modelFactory->createHyphenatedWords([$word => $hyphenatedWord])->persist();
        $this->output->printResult(['Success']);
    }
}