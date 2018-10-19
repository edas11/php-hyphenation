<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 13.29
 */

namespace Edvardas\Hyphenation\Hyphenator\Action;

use Edvardas\Hyphenation\Hyphenator\Output\BufferedOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;

class WordsGetKnownHyphenationAction implements HyphenationAction
{
    private $output;
    private $modelFactory;
    private $filterWords;

    public function __construct(HyphenationDataProvider $dataProvider, BufferedOutput $output)
    {
        $this->output = $output;
        $this->modelFactory = $dataProvider->getModelFactory();
        $this->filterWords = $dataProvider->getWordsInput();
    }

    public function execute(): void
    {
        $words = $this->modelFactory->getKnownHyphenatedWords($this->filterWords);
        $this->output->set('result', $words->getHyphenatedWords());
    }
}