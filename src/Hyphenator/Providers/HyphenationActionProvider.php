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

    public function __construct()
    {
        $this->dataProvider = new HyphenationDataProvider();
    }

    public function getAction(): Action
    {
        $choice = $this->dataProvider->getActionInput();
        switch ($choice) {
            case HyphenationDataProvider::HYPHENATE_ACTION:
                return $this->getHyphenationAction();
                break;
            case HyphenationDataProvider::PUT_PATTERNS_IN_DB_ACTION:
                return new PutPatternsInDbAction($this->dataProvider);
                break;
        }
    }

    private function getHyphenationAction(): Action
    {
        $source = $this->dataProvider->getSourceInput();
        switch ($source) {
            case HyphenationDataProvider::FILE_SRC:
                return new HyphenateWordsActionFile($this->dataProvider);
                break;
            case HyphenationDataProvider::DB_SRC:
                return new HyphenateWordsActionDB($this->dataProvider);
                break;
        }
    }
}