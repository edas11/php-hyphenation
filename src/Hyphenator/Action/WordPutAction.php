<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 14.14
 */

namespace Edvardas\Hyphenation\Hyphenator\Action;


use Edvardas\Hyphenation\Hyphenator\Model\Words;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;

class WordPutAction implements Action
{
    private $output;
    private $dataProvider;

    public function __construct(HyphenationDataProvider $dataProvider)
    {
        $this->output = $dataProvider->getOutput();
        $this->dataProvider = $dataProvider;
    }

    public function execute()
    {
        $words = $this->dataProvider->getWords();
        $hyphenatedWords = $this->dataProvider->getHyphenatedWords();
        if (count($words) < 1 || count($hyphenatedWords) < 1) {
            $this->output->printResult(['Error']);
            return;
        }
        $word = $words[0];
        $hyphenatedWord = $hyphenatedWords[0];
        (new Words([$word], [$hyphenatedWord]))->addOrUpdate();
        $this->output->printResult(['Success']);
    }
}