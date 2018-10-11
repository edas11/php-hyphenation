<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 13.35
 */

namespace Edvardas\Hyphenation\Hyphenator\Action;


use Edvardas\Hyphenation\Hyphenator\Database\HyphenationDatabase;
use Edvardas\Hyphenation\Hyphenator\Model\Patterns;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;

class PutPatternsInDbAction implements Action
{
    private $dataProvider;

    public function __construct(HyphenationDataProvider $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    public function execute()
    {
        $patterns = $this->dataProvider->loadPatterns()->persist();
    }

}