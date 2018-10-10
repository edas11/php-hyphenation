<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 13.25
 */

namespace Edvardas\Hyphenation\Hyphenator\Action;

use Edvardas\Hyphenation\App\App;
use Edvardas\Hyphenation\Hyphenator\Providers\HyphenationDataProvider;
use Edvardas\Hyphenation\UtilityComponents\Output\ConsoleOutput;
use Edvardas\Hyphenation\UtilityComponents\Timer\Timer;

class HyphenateWordsActionFile implements Action
{
    private $output;
    private $dataProvider;
    private $timer;

    public function __construct(HyphenationDataProvider $dataProvider)
    {
        $this->timer = new Timer();
        $this->output = App::getOutput();
        $this->dataProvider = $dataProvider;
    }

    public function execute()
    {
        $inputWords = $this->dataProvider->getWords();
        $patterns = $this->dataProvider->loadPatterns(false)->getPatterns();
        $algorithm = $this->dataProvider->getAlgorithm($patterns);

        $this->timer->start();

        $resultWords = [];
        foreach ($inputWords as $inputWord) {
            $word = $algorithm->execute($inputWord);
            array_push($resultWords, $word);
        }

        $this->output->printResult($resultWords);

        $this->printTime();
    }

    public function printTime(): void
    {
        $time = $this->timer->getInterval();
        $this->output->printTime($time);
        App::$logger->info("Finished in $time seconds.");
    }
}