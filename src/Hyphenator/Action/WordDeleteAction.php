<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 15.11
 */

namespace Edvardas\Hyphenation\Hyphenator\Action;


use Edvardas\Hyphenation\Hyphenator\Model\ModelFactory;
use Edvardas\Hyphenation\Hyphenator\Model\HyphenatedWords;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;

class WordDeleteAction implements Action
{
    private $output;
    private $modelFactory;
    private $inputWords;

    public function __construct(HyphenationDataProvider $dataProvider)
    {
        $this->output = $dataProvider->getOutput();
        $this->modelFactory = $dataProvider->getModelFactory();
        $this->inputWords = $dataProvider->getWordsInput();
    }

    public function execute(): void
    {
        if (count($this->inputWords) < 1) {
            $this->output->printResult(['Error']);
            return;
        }
        $word = $this->inputWords[0];
        $this->modelFactory->createHyphenatedWords([$word => ''])->delete();
        $this->output->printResult(['Success']);
    }
}