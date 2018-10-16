<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 13.35
 */

namespace Edvardas\Hyphenation\Hyphenator\Action;


use Edvardas\Hyphenation\Hyphenator\Database\HyphenationDatabase;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;

class PatternsSaveInDbAction implements Action
{
    private $dataProvider;
    private $modelFactory;

    public function __construct(HyphenationDataProvider $dataProvider)
    {
        $this->dataProvider = $dataProvider;
        $this->modelFactory = $dataProvider->getModelFactory();
    }

    public function execute(): void
    {
        $this->modelFactory->createPatternsModel($this->dataProvider->getPatternsInput())->persist();
    }

}