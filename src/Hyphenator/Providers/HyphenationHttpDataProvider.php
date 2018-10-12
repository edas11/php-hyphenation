<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 13.58
 */

namespace Edvardas\Hyphenation\Hyphenator\Providers;


use Edvardas\Hyphenation\App\App;
use Edvardas\Hyphenation\Hyphenator\Action\HyphenateAndAddToDbAction;
use Edvardas\Hyphenation\Hyphenator\Algorithm\FullTreeHyphenationAlgorithm;
use Edvardas\Hyphenation\Hyphenator\Algorithm\HyphenationAlgorithmInterface;
use Edvardas\Hyphenation\Hyphenator\Database\HyphenationDatabase;
use Edvardas\Hyphenation\Hyphenator\Input\HttpInput;
use Edvardas\Hyphenation\Hyphenator\Input\HyphenationInput;
use Edvardas\Hyphenation\Hyphenator\Model\Patterns;
use Edvardas\Hyphenation\Hyphenator\Output\HyphenationOutput;
use Edvardas\Hyphenation\UtilityComponents\Http\HttpRequest;

class HyphenationHttpDataProvider implements HyphenationDataProvider
{
    private $output;
    private $wordsArray = [];
    private $hyphenatedWordsArray = [];

    public function __construct(HyphenationOutput $output)
    {
        $this->output = $output;
    }

    public function getOutput(): HyphenationOutput
    {
        return $this->output;
    }

    public function getWords(): array
    {
        return $this->wordsArray;
    }

    public function getHyphenatedWords(): array
    {
        return $this->hyphenatedWordsArray;
    }

    public function getAlgorithm($patterns): HyphenationAlgorithmInterface
    {
        return new FullTreeHyphenationAlgorithm($patterns);
    }

    public function getPatterns(): Patterns
    {
        $patterns = Patterns::getKnown();
        return $patterns;
    }

    /**
     * @param string[] $words
     */
    public function setWords(array $words)
    {
        $this->wordsArray = $words;
    }

    /**
     * @param string[] $hyphenatedWordsArray
     */
    public function setHyphenatedWords(array $hyphenatedWordsArray)
    {
        $this->hyphenatedWordsArray = $hyphenatedWordsArray;
    }
}