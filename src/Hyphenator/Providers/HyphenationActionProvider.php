<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 09.11
 */

namespace Edvardas\Hyphenation\Hyphenator\Providers;

use Edvardas\Hyphenation\Hyphenator\Action\Action;
use Edvardas\Hyphenation\Hyphenator\Action\HyphenateWordsActionDB;
use Edvardas\Hyphenation\Hyphenator\Action\HyphenateWordsActionFile;
use Edvardas\Hyphenation\Hyphenator\Action\PutPatternsInDbAction;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;

class HyphenationActionProvider
{
    private $dataProvider;
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
        $this->dataProvider = new HyphenationDataProvider($config);
    }

    public function getAction(): Action
    {
        $choice = $this->dataProvider->getActionInput();
        switch ($choice) {
            case 1:
                return $this->getHyphenationAction();
                break;
            case 2:
                return new PutPatternsInDbAction($this->dataProvider);
                break;
        }
    }

    private function getHyphenationAction(): Action
    {
        $source = $this->dataProvider->getSourceInput();
        switch ($source) {
            case 1:
                return new HyphenateWordsActionFile($this->dataProvider);
                break;
            case 2:
                return new HyphenateWordsActionDB($this->dataProvider);
                break;
        }
    }
}