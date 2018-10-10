<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.10
 * Time: 15.57
 */

namespace Edvardas\Hyphenation\Hyphenator\Providers;

use Edvardas\Hyphenation\Hyphenator\Action\Action;
use Edvardas\Hyphenation\Hyphenator\Algorithm\HyphenationAlgorithmInterface;
use Edvardas\Hyphenation\Hyphenator\Model\Patterns;

interface HyphenationDataProvider
{
    public function getAction(): Action;

    public function getWords(): array;

    public function getAlgorithm($patterns): HyphenationAlgorithmInterface;

    public function loadPatterns(bool $isFromDb): Patterns;
}