<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.18
 * Time: 14.54
 */

namespace Edvardas\Hyphenation\Hyphenator\Action;


use Edvardas\Hyphenation\Hyphenator\Output\BufferedOutput;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;

class PatternsGetAction implements Action
{
    private $modelFactory;
    private $output;

    public function __construct(HyphenationDataProvider $dataProvider, BufferedOutput $output, int $page)
    {
        $this->output = $output;
        $this->modelFactory = $dataProvider->getModelFactory();
        $this->page = $page;
    }

    public function execute(): void
    {
        $patterns = $this->modelFactory->getPaginatedPatterns($this->page)->getPatterns();
        $this->output->set('result', $patterns);
    }
}