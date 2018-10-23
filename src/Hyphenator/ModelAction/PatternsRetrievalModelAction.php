<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.18
 * Time: 14.54
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\ModelAction;

use Edvardas\Hyphenation\Hyphenator\Model\ModelFactory;
use Edvardas\Hyphenation\Hyphenator\ModelInput\HyphenationInput;
use Edvardas\Hyphenation\Hyphenator\Output\BufferedOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;

class PatternsRetrievalModelAction implements ModelAction
{
    private $modelFactory;
    private $output;
    private $page;

    public function __construct(
        HyphenationInput $modelInput,
        BufferedOutput $output,
        ModelFactory $modelFactory,
        int $page
    ) {
        $this->output = $output;
        $this->modelFactory = $modelFactory;
        $this->page = $page;
    }

    public function execute(): void
    {
        $patterns = $this->modelFactory->getKnownPatterns($this->page)->getPatterns();
        $this->output->set('result', $patterns);
    }
}