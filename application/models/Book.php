<?php

namespace application\models;

use application\core\ActiveRecord;

/**
 * Class Book
 * @package application\models
 * @property int $id
 * @property string $title
 * @property int $isbn
 * @property string $picture
 * @property string $published
 * @property $pages_amount
 */
class Book extends ActiveRecord
{
    public static function meta()
    {
        return [
            'id' => ['type' => \PDO::PARAM_INT, 'pattern' => '^[1-9]\d*$'],
            'title' => ['type' => \PDO::PARAM_STR],
            'isbn' => ['type' => \PDO::PARAM_INT, 'pattern' => '^[1-9]\d*$'],
            'pages_amount' => ['type' => \PDO::PARAM_INT, 'pattern' => '^[1-9]\d*$'],
            'picture' => ['type'=> \PDO::PARAM_STR, 'pattern' => '\.(?:jp(?:e?g|e|2)|gif|png)$'],
            'published' => ['type' => \PDO::PARAM_INT, 'pattern' => '^[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])$']
        ];
    }

    public function validate()
    {}

    public function getAuthors()
    {
        return $this->hasMany(
            Author::class, 'author.id = author_book.author_id',
            'author_book', 'author_book.book_id = ' . $this->id
        );
    }
}
