<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 15.11
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Action;

use Edvardas\Hyphenation\Hyphenator\Model\ModelFactory;
use Edvardas\Hyphenation\Hyphenator\Model\HyphenatedWords;
use Edvardas\Hyphenation\Hyphenator\Output\BufferedOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;

class WordDeleteHyphenationAction implements HyphenationAction
{
    private $output;
    private $modelFactory;
    private $inputWords;

    public function __construct(HyphenationDataProvider $dataProvider, BufferedOutput $output)
    {
        $this->output = $output;
        $this->modelFactory = $dataProvider->getModelFactory();
        $this->inputWords = $dataProvider->getWordsInput();
    }

    public function execute(): void
    {
        if (count($this->inputWords) < 1) {
            $this->output->set('result', ['Error']);
            return;
        }
        $word = $this->inputWords[0];
        $this->modelFactory->createHyphenatedWords([$word => ''])->delete();
        $this->output->set('result', ['Success']);
    }
}