<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 13.29
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\ModelAction;

use Edvardas\Hyphenation\Hyphenator\Model\ModelFactory;
use Edvardas\Hyphenation\Hyphenator\ModelInput\HyphenationInput;
use Edvardas\Hyphenation\Hyphenator\Output\BufferedOutput;

class HyphenatedWordsRetrievalModelAction implements ModelAction
{
    private $output;
    private $modelFactory;
    private $filterWords;

    public function __construct(
        HyphenationInput $modelInput,
        BufferedOutput $output,
        ModelFactory $modelFactory
    ) {
        $this->output = $output;
        $this->modelFactory = $modelFactory;
        $this->filterWords = $modelInput->getWordsInput();
    }

    public function execute(): void
    {
        $words = $this->modelFactory->getKnownHyphenatedWords($this->filterWords);
        $this->output->set('result', $words->getHyphenatedWords());
    }
}