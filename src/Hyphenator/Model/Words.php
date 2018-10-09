<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.9
 * Time: 15.44
 */

namespace Edvardas\Hyphenation\Hyphenator\Model;

use Edvardas\Hyphenation\App\App;

class Words
{
    private $words = [];

    public function __construct(array $words)
    {
        $this->words = $words;
    }

    public function getOriginalWords(): array
    {
        return array_column($this->words, 'word');
    }

    public function getHyphenatedWords(): array
    {
        return array_column($this->words, 'word_h');
    }

    /**
     * @param string[] $words
     */
    public static function getKnown(array $words): Words
    {
        $db = App::getDb();
        $builder = $db->builder();
        $query = $builder
            ->select()
            ->columns(['word', 'word_h'])
            ->from('words')
            ->where()
            ->in('word', $words)
            ->build();
        $hyphenatedWords = $db->executeAndFetch($query);
        return new Words($hyphenatedWords);
    }
}