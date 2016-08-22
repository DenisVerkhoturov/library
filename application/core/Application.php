<?php

namespace application\core;

use PDO;

class Application
{
    /** @var mixed */
    private static $config;
    /** @var  \PDO */
    private static $db;
    /** @var Route */
    private static $route;

    public static function run($config)
    {
        self::$config = $config;
        $dsn = $config['db']['driver'] . ':host=' . $config['db']['host'] . ';dbname=' . $config['db']['database'];
        try {
            self::$db = new PDO($dsn, $config['db']['user'], $config['db']['password']);
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die(new \Exception('Database exception'));
        }

        self::$route = new Route();
    }

    /**
     * @return \PDO
     */
    public static function getPDO()
    {
        return self::$db;
    }

    public static function getIndexController()
    {
        if (isset(self::$config['index']['controller'])) {
            $controller = self::$config['index']['controller'];
            return new $controller;
        }
        else {
            trigger_error('Index controller is not defined', E_USER_ERROR);
            return NULL;
        }
    }

    public static function getIndexAction()
    {
        if (isset(self::$config['index']['action'])) {
            return self::$config['index']['action'];
        }
        else {
            trigger_error('Index action is not defined', E_USER_ERROR);
            return NULL;
        }
    }

    public static function NotFoundException()
    {
        View::render('base', '404', ['page_title' => '404']);
    }
}
