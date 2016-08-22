<?php

namespace application\models;

use application\Application;
use application\core\ActiveRecord;

/**
 * Class AuthorBook
 * @package application\models
 * @property int $book_id
 * @property int $author_id
 */
class AuthorBook extends ActiveRecord
{
    public static function getTableName()
    {
        return 'author_book';
    }

    public static function hasManyAuthors($book_id)
    {
    }
}
