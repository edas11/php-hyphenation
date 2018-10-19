<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.9
 * Time: 16.39
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Model;

use Edvardas\Hyphenation\Hyphenator\Model\MappingStrategy\WordPatternsMappingStrategy;
use Edvardas\Hyphenation\UtilityComponents\Database\SqlDatabase;

class WordPatterns implements PersistentModel
{
    private $wordPatterns;
    private $db;

    /**
     * @param string[][] $wordPatterns
     */
    public function __construct(array $wordPatterns, SqlDatabase $db)
    {
        $this->db = $db;
        $this->wordPatterns = $wordPatterns;
    }

    public function getMatchedPatterns()
    {
        return $this->wordPatterns;
    }

    public static function getKnown(array $words, SqlDatabase $db): WordPatterns
    {
        $builder = $db->builder();
        $token = $db->beginTransaction();
        $query = $builder
            ->select()
            ->columns(['word', 'pattern'])
            ->from('word_patterns')
            ->join('words', 'word_patterns.word_id', 'words.word_id')
            ->join('patterns', 'word_patterns.pattern_id', 'patterns.pattern_id')
            ->where()
            ->in('words.word', $words)
            ->build();
        $wordMatchedPatterns = $db->executeAndFetch($query, new WordPatternsMappingStrategy());
        $db->commit($token);

        return new WordPatterns($wordMatchedPatterns, $db);
    }

    public function persist(): void
    {
        $token = $this->db->beginTransaction();
        $builder = $this->db->builder();
        foreach ($this->wordPatterns as $word => $matchedPatterns) {
            if (count($matchedPatterns) > 0){
                $querry = $builder
                    ->replace()
                    ->into('word_patterns', ['word_id', 'pattern_id'])
                    ->select()
                    ->columns(['word_id', 'pattern_id'])
                    ->from('words, patterns')->where()
                    ->equals('word', $word)
                    ->and()
                    ->in('pattern', $matchedPatterns)
                    ->build();
                $this->db->execute($querry);
            }
        }
        $this->db->commit($token);
    }
}