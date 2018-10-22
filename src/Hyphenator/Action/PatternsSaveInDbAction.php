<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 13.35
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Action;

use Edvardas\Hyphenation\Hyphenator\Database\HyphenationDatabase;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;

class PatternsSaveInDbAction implements Action
{
    private $modelFactory;
    private $patternsInput;

    public function __construct(HyphenationDataProvider $dataProvider)
    {
        $this->modelFactory = $dataProvider->getModelFactory();
        $this->patternsInput = $dataProvider->getPatternsInput();
    }

    public function execute(): void
    {
        $this->modelFactory->createHyphenatedWords([])->deleteAll();
        $this->modelFactory->createPatternsModel($this->patternsInput)->persist();
    }

}