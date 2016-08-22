<?php

namespace application\controllers;

use application\core\Application;
use application\core\Controller;
use application\models\Author as Model;
use application\core\View;

class Author extends Controller
{
    public function viewAction($id)
    {
        $id = is_int($id) ? $id : intval($id);
        $model = Model::findById($id);
        if (!empty($model)) {
            View::render('base', 'author/details', [
                'page_title' => $model->getShortName(),
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

        View::render('base', 'author/form',
            [
                'page_title' => 'Редактирование автора',
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
