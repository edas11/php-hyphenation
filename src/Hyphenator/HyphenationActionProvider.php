<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 09.11
 */

namespace Edvardas\Hyphenation\Hyphenator;

use Edvardas\Hyphenation\Hyphenator\Action\HyphenateWordsAction;
use Edvardas\Hyphenation\Hyphenator\Action\PutPatternsInDbAction;
use Edvardas\Hyphenation\Hyphenator\HyphenationDataProvider;

class HyphenationActionProvider
{
    private $dataProvider;
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
        $this->dataProvider = new HyphenationDataProvider($config);
    }

    public function getAction()
    {
        $choice = $this->dataProvider->getActionInput();
        switch ($choice) {
            case 1:
                return new HyphenateWordsAction($this->config);
                break;
            case 2:
                return new PutPatternsInDbAction($this->config);
                break;
        }
    }
}