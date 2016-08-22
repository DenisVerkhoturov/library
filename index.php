<?php

error_reporting(E_ALL);

require_once 'vendor/autoload.php';
$config = require_once 'application/config/config.php';

\application\core\Application::run($config);
