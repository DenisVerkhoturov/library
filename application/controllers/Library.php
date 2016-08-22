<?php

namespace application\controllers;

use application\models\Book as Model;
use application\core\View;
use application\core\Controller;

class Library extends Controller
{
    const BOOKS_PER_PAGE = 15;

    public function booksAction($page_number = 0)
    {
        $page_number = is_int($page_number) ? $page_number : intval($page_number);
        $models = Model::findMultiple($page_number, self::BOOKS_PER_PAGE);
        View::render('base', 'book/list', ['page_title' => 'Our books', 'models' => $models]);
    }
}
