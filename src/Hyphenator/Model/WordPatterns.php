<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.9
 * Time: 16.39
 */

namespace Edvardas\Hyphenation\Hyphenator\Model;


use Edvardas\Hyphenation\App\App;

class WordPatterns
{
    private $wordPatterns;

    public function __construct(array $wordPatterns)
    {
        $this->wordPatterns = $wordPatterns;
    }

    public function getMatchedPatterns()
    {
        $matchedPatternsResult = [];
        foreach ($this->wordPatterns as $match) {
            if (array_key_exists($match['word'], $matchedPatternsResult)) {
                array_push($matchedPatternsResult[$match['word']], $match['pattern']);
            } else {
                $matchedPatternsResult[$match['word']] = [$match['pattern']];
            }
        }
        return $matchedPatternsResult;
    }

    public static function getKnown(array $words): WordPatterns
    {
        $db = App::getDb();
        $builder = $db->builder();
        $query = $builder
            ->select()
            ->columns(['word', 'pattern'])
            ->from('word_patterns')
            ->join('words', 'word_patterns.word_id', 'words.word_id')
            ->join('patterns', 'word_patterns.pattern_id', 'patterns.pattern_id')
            ->where()
            ->in('words.word', $words)
            ->build();
        $wordMatchedPatterns = $db->executeAndFetch($query);
        return new WordPatterns($wordMatchedPatterns);
    }

    public static function putWordsAndMatchedPatterns(array $words, array $hyphWords, array $matchedPatternsAll)
    {
        if (count($words) !== count($hyphWords) || count($hyphWords) !== count($matchedPatternsAll)) {
            throw new \Exception('All 3 array must have the same length.');
        }
        if (count($words) === 0) {
            return;
        }
        $db = App::getDb();
        $builder = $db->builder();
        $words = array_values($words);
        $wordsMatrix = [];
        foreach ($words as $index => $word) {
            array_push($wordsMatrix, [$word, $hyphWords[$index]]);
        }
        $querry = $builder
            ->insert()
            ->into('words', ['word, word_h'])
            ->values($wordsMatrix)
            ->build();
        $db->execute($querry);

        foreach ($words as $index => $word) {
            $querry = $builder
                ->insert()
                ->into('word_patterns', ['word_id', 'pattern_id'])
                ->select()
                ->columns(['word_id', 'pattern_id'])
                ->from('words, patterns')->where()
                ->equals('word', $word)
                ->and()
                ->in('pattern', $matchedPatternsAll[$index])
                ->build();
            $db->execute($querry);
        }
    }
}