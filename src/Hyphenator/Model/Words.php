<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.9
 * Time: 15.44
 */

namespace Edvardas\Hyphenation\Hyphenator\Model;

use Edvardas\Hyphenation\App\App;

class Words implements PersistentModel
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
    public static function getKnownIn(array $words): Words
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
        $db->beginTransaction();
        $hyphenatedWords = $db->executeAndFetch($query);
        $db->commit();
        return new Words($hyphenatedWords);
    }

    public static function getKnown(): Words
    {
        $db = App::getDb();
        $builder = $db->builder();
        $query = $builder
            ->select()
            ->columns(['word', 'word_h'])
            ->from('words')
            ->build();
        $db->beginTransaction();
        $hyphenatedWords = $db->executeAndFetch($query);
        $db->commit();
        return new Words($hyphenatedWords);
    }

    public static function newFromColumnArrays(array $originalWords, array $hyphenatedWords)
    {
        $wordsTable = [];
        foreach ($originalWords as $index => $word) {
            array_push($wordsTable, ['word' => $word, 'word_h' => $hyphenatedWords[$index]]);
        }
        return new Words($wordsTable);
    }

    public function update(): void
    {
        $db = App::getDb();
        $builder = $db->builder();
        $db->beginTransaction();
        foreach ($this->words as $wordRow) {
            $querry = $builder
                ->update('words')
                ->set(['word_h' => $wordRow['word_h']])
                ->where()
                ->equals('word', $wordRow['word'])
                ->build();
            $db->execute($querry);
        }
        $db->commit();
    }

    public function delete(): void
    {
        $db = App::getDb();
        $builder = $db->builder();
        $db->beginTransaction();
        foreach ($this->words as $wordRow) {
            $querry = $builder
                ->delete()
                ->from('words')
                ->where()
                ->equals('word', $wordRow['word'])
                ->build();
            $db->execute($querry);
        }
        $db->commit();
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
        $querry = $builder
            ->insert()
            ->into('words', ['word, word_h'])
            ->values($this->words)
            ->build();
        $db->execute($querry);
    }
}