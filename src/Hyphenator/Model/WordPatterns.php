<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.9
 * Time: 16.39
 */
declare(strict_types = 1);
namespace Edvardas\Hyphenation\Hyphenator\Model;


use Edvardas\Hyphenation\App\App;
use Edvardas\Hyphenation\Hyphenator\Model\MappingStrategy\WordPatternsMappingStrategy;

class WordPatterns implements PersistentModel
{
    private $wordPatterns;

    /**
     * @param string[][] $wordPatterns
     */
    public function __construct(array $wordPatterns)
    {
        $this->wordPatterns = $wordPatterns;
    }

    public function getMatchedPatterns()
    {
        return $this->wordPatterns;
    }

    public static function getKnown(array $words): WordPatterns
    {
        $db = App::getDb();
        $builder = $db->builder();
        $db->beginTransaction();
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
        $db->commit();

        return new WordPatterns($wordMatchedPatterns);
    }

    public function persist(): void
    {
        $db = App::getDb();
        $db->beginTransaction();
        $this->persistNoTransaction();
        $db->commit();
    }

    public function persistNoTransaction(): void
    {
        $db = App::getDb();
        $builder = $db->builder();
        foreach ($this->wordPatterns as $word => $matchedPatterns) {
            foreach ($matchedPatterns as $pattern) {
                $querry = $builder
                    ->insert()
                    ->into('word_patterns', ['word_id', 'pattern_id'])
                    ->select()
                    ->columns(['word_id', 'pattern_id'])
                    ->from('words, patterns')->where()
                    ->equals('word', $word)
                    ->and()
                    ->equals('pattern', $pattern)
                    ->build();
                $db->execute($querry);
            }
        }
    }
}