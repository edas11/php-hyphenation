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
    private $output;

    public function __construct(HyphenationDataProvider $dataProvider)
    {
        $this->output = $dataProvider->getOutput();
    }

    public function execute(): void
    {
        http_response_code(400);
        $this->output->printError('Bad request');
    }
}