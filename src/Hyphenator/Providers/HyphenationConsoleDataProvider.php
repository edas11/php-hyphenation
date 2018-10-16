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
use Edvardas\Hyphenation\Hyphenator\Algorithm\ShortTreeHyphenationAlgorithm;
use Edvardas\Hyphenation\Hyphenator\Database\HyphenationDatabase;
use Edvardas\Hyphenation\Hyphenator\File\PatternsFile;
use Edvardas\Hyphenation\Hyphenator\File\WordsFile;
use Edvardas\Hyphenation\Hyphenator\Input\ConsoleInput;
use Edvardas\Hyphenation\Hyphenator\Input\HyphenationInput;
use Edvardas\Hyphenation\Hyphenator\Input\InputCodes;
use Edvardas\Hyphenation\Hyphenator\Model\ModelFactory;
use Edvardas\Hyphenation\Hyphenator\Model\Patterns;
use Edvardas\Hyphenation\Hyphenator\Output\HyphenationOutput;
use Edvardas\Hyphenation\UtilityComponents\Config\Config;

class HyphenationConsoleDataProvider implements HyphenationDataProvider
{
    private $input;
    private $output;
    private $config;
    private $modelFactory;

    public function __construct(ConsoleInput $input, HyphenationOutput $output, Config $config, ModelFactory $modelFactory)
    {
        $this->input = $input;
        $this->output = $output;
        $this->config = $config;
        $this->modelFactory = $modelFactory;
    }

    public function getOutput(): HyphenationOutput
    {
        return $this->output;
    }

    public function getModelFactory(): ModelFactory
    {
        return $this->modelFactory;
    }

    public function getWords(): array
    {
        $wordsInput = $this->input->getWordsInput();
        if ($wordsInput === '') {
            $wordsFileName = $this->config->get(['wordsFileName'], 'words.txt');
            App::$logger->info("Reading words from $wordsFileName file.");
            $words = WordsFile::getContentsAsArray($wordsFileName);
        } else {
            $words = explode(' ', $wordsInput);
        }
        App::wordsReadEvent(count($words));
        return $words;
    }

    public function getHyphenatedWords(): array
    {
        $hyphenatedWordsInput = $this->input->getHyphenatedWordsInput();
        return explode(' ', $hyphenatedWordsInput);
    }

    public function getAlgorithm($patterns): HyphenationAlgorithmInterface
    {
        $algorithmChoice = $this->input->getAlgorithmInput();
        switch ($algorithmChoice) {
            case InputCodes::FULL_TREE_ALGORITHM:
                return new FullTreeHyphenationAlgorithm($patterns);
                break;
            case InputCodes::SHORT_TREE_ALGORITHM:
                return new ShortTreeHyphenationAlgorithm($patterns);
                break;
            default:
                return new FullTreeHyphenationAlgorithm($patterns);
        }
    }

    public function getPatterns(): Patterns
    {
        if ($this->input->getSourceInput() === InputCodes::DB_SRC) {
            $patterns = $this->modelFactory->getKnownPatterns();
        } else {
            $patternsFileName = $this->config->get(['patternsFileName'], 'patterns');
            $patterns = new Patterns(PatternsFile::getContentsAsArray($patternsFileName));
        }
        return $patterns;
    }
}