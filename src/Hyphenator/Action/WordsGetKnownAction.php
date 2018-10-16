<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 13.29
 */

namespace Edvardas\Hyphenation\Hyphenator\Action;


use Edvardas\Hyphenation\Hyphenator\Model\Words;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;

class WordsGetKnownAction implements Action
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
        if (count($words) > 0) {
            $words = Words::getKnownIn($words);
        } else {
            $words = Words::getKnown();
        }
        $this->output->printResult(array_combine($words->getOriginalWords(), $words->getHyphenatedWords()));
    }
}