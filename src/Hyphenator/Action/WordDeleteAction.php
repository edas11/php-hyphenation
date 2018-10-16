<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 15.11
 */

namespace Edvardas\Hyphenation\Hyphenator\Action;


use Edvardas\Hyphenation\Hyphenator\Model\Words;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;

class WordDeleteAction implements Action
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
        if (count($words) < 1) {
            $this->output->printResult(['Error']);
            return;
        }
        $word = $words[0];
        (new Words([$word => '']))->delete();
        $this->output->printResult(['Success']);
    }
}