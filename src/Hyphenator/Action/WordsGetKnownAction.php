<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 13.29
 */

namespace Edvardas\Hyphenation\Hyphenator\Action;

use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;

class WordsGetKnownAction implements Action
{
    private $output;
    private $dataProvider;
    private $modelFactory;

    public function __construct(HyphenationDataProvider $dataProvider)
    {
        $this->output = $dataProvider->getOutput();
        $this->dataProvider = $dataProvider;
        $this->modelFactory = $dataProvider->getModelFactory();
    }

    public function execute(): void
    {
        $filterWords = $this->dataProvider->getWordsInput();
        $words = $this->modelFactory->getKnownHyphenatedWords($filterWords);
        $this->output->printResult($words->getWords());
    }
}