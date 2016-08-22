<?php

namespace application\controllers;

use application\Application;
use application\core\Controller;
use application\models\Book as Model;
use application\core\View;

class Book extends Controller
{
    public function viewAction($id)
    {
        $id = is_int($id) ? $id : intval($id);
        $model = Model::findById($id);
        if (!empty($model)) {
            View::render('base', 'book/details', [
                'page_title' => $model->title,
                'model' => $model
            ]);
        }
        else {
            Application::NotFoundException();
        }
    }

    public function editAction($id = NULL)
    {
        $model = empty($id) ? new Model() : Model::findById($id);
        $messages = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model->load($_POST);
            try {
                $model->save();
            } catch (\Exception $e) {
                $messages[] = $e->getMessage();
            }
        }

        View::render('base', 'book/form',
            [
                'page_title' => 'Редактирование книги',
                'model' => $model,
                'messages' => $messages
            ]
        );
    }

    public function deleteAction($id)
    {
        $model = empty($id) ? new Model() : Model::findById($id);
        $model->delete();
    }
}
