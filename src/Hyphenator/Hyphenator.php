<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.5
 * Time: 16.05
 */

namespace Edvardas\Hyphenation\Hyphenator;

use Edvardas\Hyphenation\UtilityComponents\Config\Config;

class Hyphenator
{
    private $provider;

    public function __construct(Config $config)
    {
        $this->provider = new HyphenationAlgorithmProvider($config);
    }

    public function hyphenateWords() {
        $inputWords = $this->provider->getWords();
        $algorithm = $this->provider->getAlgorithm();

        $result = [];
        foreach ($inputWords as $inputWord) {
            array_push($result, $algorithm->execute($inputWord));
        }
        return $result;
    }
}