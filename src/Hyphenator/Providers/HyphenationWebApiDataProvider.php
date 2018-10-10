<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.10
 * Time: 16.01
 */

namespace Edvardas\Hyphenation\Hyphenator\Providers;


use Edvardas\Hyphenation\App\App;
use Edvardas\Hyphenation\Hyphenator\Action\Action;
use Edvardas\Hyphenation\Hyphenator\Action\HyphenateWordsActionFile;
use Edvardas\Hyphenation\Hyphenator\Algorithm\FullTreeHyphenationAlgorithm;
use Edvardas\Hyphenation\Hyphenator\Algorithm\HyphenationAlgorithmInterface;
use Edvardas\Hyphenation\Hyphenator\Model\Patterns;

class HyphenationWebApiDataProvider implements HyphenationDataProvider
{

    public function getAction(): Action
    {
        return new HyphenateWordsActionFile($this);
    }

    public function getWords(): array
    {
        return ['mistranslate'];
    }

    public function getAlgorithm($patterns): HyphenationAlgorithmInterface
    {
        return new FullTreeHyphenationAlgorithm($patterns);
    }

    public function loadPatterns(bool $isFromDb): Patterns
    {
        $patternsFileName = App::getConfig()->get(['patternsFileName'], 'patterns');
        $patterns = file($patternsFileName, FILE_IGNORE_NEW_LINES);
        if ($patterns === false) {
            App::$logger->error("Could not read patterns file.");
            exit;
        }
        $patterns = array_map(function ($pattern) {
            return ['pattern' => $pattern];
        }, $patterns);
        $patterns = new Patterns($patterns);
        return $patterns;
    }
}