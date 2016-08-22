<?php

namespace application\core;

class View
{
    public static function render($layout, $view, $parameters = [])
    {
        $layout = __DIR__ . '/../layouts/' . $layout . '.php';
        $view = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($layout)) {
            extract($parameters);
            include $layout;
        }
    }
}
