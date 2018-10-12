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

class PutWordAction implements Action
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
        $word = implode('', $this->dataProvider->getWords());
        $hyphenatedWord = implode('', $this->dataProvider->getHyphenatedWords());
        Words::newFromColumnArrays([$word], [$hyphenatedWord])->addOrUpdate();
        $this->output->printResult(['Success']);
    }
}