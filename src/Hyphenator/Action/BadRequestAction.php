<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.11
 * Time: 12.29
 */

namespace Edvardas\Hyphenation\Hyphenator\Action;

use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;

class BadRequestAction implements Action
{
    private $dataProvider;

    public function __construct(HyphenationDataProvider $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    public function execute()
    {
        http_response_code(400);
        $this->dataProvider->getOutput()->printError('Bad request');
    }
}