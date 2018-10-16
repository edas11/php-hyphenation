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
use Edvardas\Hyphenation\Hyphenator\Model\MappingStrategy\WordsMappingStrategy;

class Words implements PersistentModel
{
    private $words = [];
    public const WORD_COLUMN = 'word';
    public const HYPHENATED_WORD_COLUMN = 'word_h';

    public function __construct(array $words)
    {
        $this->words = $words;
    }

    public function getOriginalWords(): array
    {
        return array_keys($this->words);
    }

    public function getHyphenatedWords(): array
    {
        return array_values($this->words);
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
        $hyphenatedWords = $db->executeAndFetch($query, new WordsMappingStrategy());
        $db->commit();
        return new Words($hyphenatedWords);
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
        $hyphenatedWords = $db->executeAndFetch($query, new WordsMappingStrategy());
        $db->commit();
        return new Words($hyphenatedWords);
    }

    public function addOrUpdate(): void
    {
        $db = App::getDb();
        $db->beginTransaction();
        foreach ($this->words as $word => $hyphenatedWord) {
            if ($this->doesWordExist($word)) {
                $this->updateWord($word, $hyphenatedWord);
            } else {
                $this->addWord($word, $hyphenatedWord);
            }
        }
        $db->commit();
    }

    private function doesWordExist(string $word)
    {
        $db = App::getDb();
        $builder = $db->builder();
        $query = $builder
            ->select()
            ->columns(['*'])
            ->from('words')
            ->where()
            ->equals(self::WORD_COLUMN, $word)
            ->build();
        $result = $db->executeAndFetch($query);
        return count($result) > 0;
    }

    private function updateWord(string $word, string $hyphenatedWord)
    {
        $db = App::getDb();
        $builder = $db->builder();
        $query = $builder
            ->update('words')
            ->set([self::HYPHENATED_WORD_COLUMN => $hyphenatedWord])
            ->where()
            ->equals(self::WORD_COLUMN, $word)
            ->build();
        $db->execute($query);
    }

    private function addWord(string $word, string $hyphenatedWord)
    {
        $db = App::getDb();
        $builder = $db->builder();
        $query = $builder->insert()
        ->into('words', ['word, word_h'])
        ->values([$word, $hyphenatedWord])
        ->build();
        $db->execute($query);
    }

    public function delete(): void
    {
        $db = App::getDb();
        $builder = $db->builder();
        $db->beginTransaction();
        foreach ($this->words as $word => $hyphenatedWord) {
            $query = $builder
                ->delete()
                ->from('words')
                ->where()
                ->equals(self::WORD_COLUMN, $word)
                ->build();
            $db->execute($query);
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
        $builder = $builder
            ->insert()
            ->into('words', ['word, word_h']);
        foreach ($this->words as $word => $hyphenatedWord) {
            $builder = $builder->values([$word, $hyphenatedWord]);
        }
        $query = $builder->build();
        $db->execute($query);
    }
}