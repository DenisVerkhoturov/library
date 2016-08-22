<?php

namespace application\core;

class Route
{
    private $request;
    private $controller;
    private $action;
    private $arguments = [];

    public function __construct()
    {
        $this->request = strtolower(trim($_SERVER['REQUEST_URI'], '/'));

        if (empty($this->request)) {
            $this->controller = Application::getIndexController();
            $this->action = Application::getIndexAction();
            $this->getResponse();
        } else {
            $uri = explode('/', $this->request);
            $this->controller = 'application\controllers\\' . ucfirst(array_shift($uri));
            if (class_exists($this->controller)) {
                $this->controller = new $this->controller;
                $action_name = array_shift($uri);
                if (empty($action_name)) $action_name = 'index';
                $action_name = $action_name . 'Action';
                if (method_exists($this->controller, $action_name)) {
                    $this->action = $action_name;
                    $this->arguments[] = array_shift($uri);
                    $this->getResponse();
                } else {
                    Application::NotFoundException();
                }
            }
            else {
                Application::NotFoundException();
            }
        }
    }

    public function getResponse()
    {
        return call_user_func_array(
            [
                $this->controller,
                $this->action
            ],
            $this->arguments
        );
    }
}
