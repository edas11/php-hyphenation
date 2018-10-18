<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.18
 * Time: 11.38
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Action;

use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;

class PageGetAction implements Action
{
    private $output;
    private $pagePath;

    public function __construct(HyphenationDataProvider $dataProvider, string $pagePath)
    {
        $this->output = $dataProvider->getOutput();
        $this->pagePath = $pagePath;
    }

    public function execute(): void
    {
        $this->output->printPage($this->pagePath);
    }
}