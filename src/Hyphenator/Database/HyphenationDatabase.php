<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.8
 * Time: 10.32
 */

namespace Edvardas\Hyphenation\Hyphenator\Database;

use Edvardas\Hyphenation\App\App;
use Edvardas\Hyphenation\UtilityComponents\Database\MySqlDatabase;
use Edvardas\Hyphenation\UtilityComponents\Database\MySqlQueryBuilder;

class HyphenationDatabase
{
    private $db;
    private $builder;
    private $patterns;

    public function __construct()
    {
        $this->builder = new MySqlQueryBuilder();
        $host = App::getConfig()->get(['mysql', 'host']);
        $db = App::getConfig()->get(['mysql', 'db']);
        $user = App::getConfig()->get(['mysql', 'user']);
        $pass = App::getConfig()->get(['mysql', 'password']);
        $charset = App::getConfig()->get(['mysql', 'charset']);
        $this->db = new MySqlDatabase($host, $db, $user, $pass, $charset);
    }

    public function getPatterns()
    {
        $query = $this->builder->select()->columns(['pattern_id', 'pattern'])->from('patterns')->build();
        $patterns = $this->db->executeAndFetch($query);
        $patterns = array_map(function($value){
            return $value['pattern'];
        }, $patterns);
        return $patterns;
    }

    public function getKnownHyphenatedWords(array $words): array
    {
        $query = $this->builder->select()->columns(['word', 'word_h'])->from('words')
            ->where()->in('word', $words)->build();
        $hyphenatedWords = $this->db->executeAndFetch($query);
        return $hyphenatedWords;
    }

    public function getWordMatchedPatterns(array $words): array
    {
        $query = $this->builder->select()->columns(['word', 'pattern'])->from('word_patterns')
            ->join('words', 'word_patterns.word_id', 'words.word_id')
            ->join('patterns', 'word_patterns.pattern_id', 'patterns.pattern_id')
            ->where()->in('words.word', $words)->build();
        $wordMatchedPatterns = $this->db->executeAndFetch($query);
        return $wordMatchedPatterns;
    }

    public function putPatterns(array $patterns)
    {
        $patternsMatrix = array_map(function ($pattern) {
            return [$pattern];
        }, $patterns);
        $query = $this->builder->delete()->from('patterns')->build();
        $this->db->execute($query);
        $query = $this->builder->insert()->into('patterns', ['pattern'])->values($patternsMatrix)->build();
        $this->db->execute($query);
    }

    public function putWordsAndMatchedPatterns(array $words, array $hyphWords, array $matchedPatternsAll)
    {
        if (count($words) !== count($hyphWords) || count($hyphWords) !== count($matchedPatternsAll)) {
            throw new \Exception('All 3 array must have the same length.');
        }
        if (count($words) === 0) {
            return;
        }
        $wordsMatrix = [];
        foreach ($words as $index => $word) {
            array_push($wordsMatrix, [$word, $hyphWords[$index]] );
        }
        $querry = $this->builder->insert()->into('words', ['word, word_h'])->values($wordsMatrix)->build();
        $this->db->execute($querry);

        foreach ($words as $index => $word){
            $querry = $this->builder->insert()->into('word_patterns', ['word_id', 'pattern_id'])
                ->select()->columns(['word_id', 'pattern_id'])->from('words, patterns')->where()
                ->equals('word', $word)->and()->in('pattern', $matchedPatternsAll[$index])->build();
            $this->db->execute($querry);
        }
    }
}