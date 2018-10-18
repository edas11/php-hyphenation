<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.18
 * Time: 14.54
 */

namespace Edvardas\Hyphenation\Hyphenator\Action;


use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;

class PatternsGetAction implements Action
{
    private $modelFactory;
    private $output;

    public function __construct(HyphenationDataProvider $dataProvider)
    {
        $this->output = $dataProvider->getOutput();
        $this->modelFactory = $dataProvider->getModelFactory();
    }

    public function execute(): void
    {
        $patterns = $this->modelFactory->getKnownPatterns()->getPatterns();
        $this->output->printResult($patterns);
    }
}