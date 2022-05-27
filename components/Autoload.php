<?php

spl_autoload_register(function($class_name) {
    $array_path = ['/models/', '/controllers/', '/components/'];

    foreach ($array_path as $path) {
        $path = ROOT . $path . $class_name . '.php';
        if (file_exists($path)) {
            require_once $path;
        }
    }
});

