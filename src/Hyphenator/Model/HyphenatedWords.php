<?php
/**
 * Created by PhpStorm.
 * User: edvardas
 * Date: 18.10.9
 * Time: 15.44
 */
declare(strict_types = 1);

namespace Edvardas\Hyphenation\Hyphenator\Model;

use Edvardas\Hyphenation\Hyphenator\Model\MappingStrategy\WordsMappingStrategy;
use Edvardas\Hyphenation\UtilityComponents\Database\SqlDatabase;

class HyphenatedWords implements PersistentModel
{
    private $db;
    private $words = [];
    public const WORD_COLUMN = 'word';
    public const HYPHENATED_WORD_COLUMN = 'word_h';
    public const WORDS_TABLE = 'words';

    public function __construct(array $words, SqlDatabase $db)
    {
        $this->words = $words;
        $this->db = $db;
    }

    public function getWords(): array
    {
        return $this->words;
    }

    public function filterUnknownWords(array $inputWords)
    {
        return array_values(array_diff($inputWords, array_keys($this->words)));
    }

    /**
     * @param string[] $words
     */
    public static function getKnownIn(SqlDatabase $db, array $words = []): HyphenatedWords
    {
        $builder = $db->builder();
        $builder->select()
            ->columns([self::WORD_COLUMN, self::HYPHENATED_WORD_COLUMN])
            ->from(self::WORDS_TABLE);
        if (count($words) > 0) {
            $builder->where()
                ->in(self::WORD_COLUMN, $words);
        }
        $query = $builder->build();
        $token = $db->beginTransaction();
        $hyphenatedWords = $db->executeAndFetch($query, new WordsMappingStrategy());
        $db->commit($token);
        return new HyphenatedWords($hyphenatedWords, $db);
    }

    public function delete(): void
    {
        $builder = $this->db->builder();
        $token = $this->db->beginTransaction();
        foreach ($this->words as $word => $hyphenatedWord) {
            $query = $builder
                ->delete()
                ->from(self::WORDS_TABLE)
                ->where()
                ->equals(self::WORD_COLUMN, $word)
                ->build();
            $this->db->execute($query);
        }
        $this->db->commit($token);
    }

    public function persist(): void
    {
        $token = $this->db->beginTransaction();
        $builder = $this->db->builder();
        $builder = $builder
            ->replace()
            ->into(self::WORDS_TABLE, [self::WORD_COLUMN, self::HYPHENATED_WORD_COLUMN]);
        foreach ($this->words as $word => $hyphenatedWord) {
            $builder = $builder->values([$word, $hyphenatedWord]);
        }
        $query = $builder->build();
        $this->db->execute($query);
        $this->db->commit($token);
    }
}