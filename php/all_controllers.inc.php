<?php

require_once 'BaseController.php';

//require_once "headerPageController.php";
//require_once "mainPageController.php";
//require_once "userPageController.php";

function autoloader($class) {
    
    if (preg_match('/Controller/', $class)) {
        $controller_file = preg_replace('/Controller/', '', $class);
        if (file_exists('php/' . lcfirst($controller_file) . 'Controller.php'))
            $controller_file = lcfirst($controller_file) . 'Controller.php';
        else
            $controller_file = ucfirst($controller_file) . 'Controller.php';

        if (file_exists(MAIN_PATH . 'php/' . $controller_file))
            require_once MAIN_PATH . 'php/' . $controller_file;
    }
}

spl_autoload_register('autoloader');