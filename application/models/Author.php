<?php

namespace application\models;

use application\core\ActiveRecord;

/**
 * Class Author
 * @package application\models
 * @property int $id
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 */
class Author extends ActiveRecord
{
    public static function meta()
    {
        return [
            'id' => ['type' => \PDO::PARAM_INT, 'pattern' => '^[1-9]\d*$'],
            'first_name' => ['type' => \PDO::PARAM_STR],
            'middle_name' => ['type' => \PDO::PARAM_STR],
            'last_name' => ['type' => \PDO::PARAM_STR],
        ];
    }

    public function getBooks()
    {
        return $this->hasMany(
            Book::class, 'book.id = author_book.book_id',
            'author_book', 'author_book.author_id = ' . $this->id
        );
    }

    public function getShortName()
    {
        return $this->last_name . ' ' . $this->first_name[0] . '. ' .$this->middle_name[0] . '.';
    }
}
