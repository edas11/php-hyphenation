<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.9
 * Time: 15.44
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Model;

use Edvardas\Hyphenation\App\App;

class Words implements PersistentModel
{
    private $words = [];
    private const WORD_COLUMN = 'word';
    private const HYPHENATED_WORD_COLUMN = 'word_h';

    public function __construct(array $originalWords, array $hyphenatedWords)
    {
        $this->words = self::createTable($originalWords, $hyphenatedWords);
    }

    private static function createFromDbData(array $dbWords)
    {
        return new Words(
            array_column($dbWords, self::WORD_COLUMN),
            array_column($dbWords, self::HYPHENATED_WORD_COLUMN)
        );
    }

    public function getOriginalWords(): array
    {
        return array_column($this->words, self::WORD_COLUMN);
    }

    public function getHyphenatedWords(): array
    {
        return array_column($this->words, self::HYPHENATED_WORD_COLUMN);
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
            ->columns([self::WORD_COLUMN, self::HYPHENATED_WORD_COLUMN])
            ->from('words')
            ->where()
            ->in(self::WORD_COLUMN, $words)
            ->build();
        $db->beginTransaction();
        $hyphenatedWords = $db->executeAndFetch($query);
        $db->commit();
        return self::createFromDbData($hyphenatedWords);
    }

    public static function getKnown(): Words
    {
        $db = App::getDb();
        $builder = $db->builder();
        $query = $builder
            ->select()
            ->columns([self::WORD_COLUMN, self::HYPHENATED_WORD_COLUMN])
            ->from('words')
            ->build();
        $db->beginTransaction();
        $hyphenatedWords = $db->executeAndFetch($query);
        $db->commit();
        return self::createFromDbData($hyphenatedWords);
    }

    public function addOrUpdate(): void
    {
        $db = App::getDb();
        $builder = $db->builder();
        $db->beginTransaction();
        foreach ($this->words as $wordRow) {
            $querry = $builder
                ->select()
                ->columns(['*'])
                ->from('words')
                ->where()
                ->equals(self::WORD_COLUMN, $wordRow['word'])
                ->build();
            $result = $db->executeAndFetch($querry);
            if (count($result) > 0) {
                $querry = $builder
                    ->update('words')
                    ->set([self::HYPHENATED_WORD_COLUMN => $wordRow[self::HYPHENATED_WORD_COLUMN]])
                    ->where()
                    ->equals(self::WORD_COLUMN, $wordRow['word'])
                    ->build();
                $db->execute($querry);
            } else {
                $querry = $builder
                    ->insert()
                    ->into('words', ['word, word_h'])
                    ->values([$wordRow])
                    ->build();
                $db->execute($querry);
            }
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
                ->equals(self::WORD_COLUMN, $wordRow['word'])
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

    private static function createTable(array $originalWords, array $hyphenatedWords): array
    {
        $wordsTable = [];
        foreach ($originalWords as $index => $word) {
            array_push(
                $wordsTable,
                [self::WORD_COLUMN => $word, self::HYPHENATED_WORD_COLUMN => $hyphenatedWords[$index]]
            );
        }
        return $wordsTable;
    }
}