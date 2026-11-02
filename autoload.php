<?php

spl_autoload_register(function ($class) {
    $prefix = "App\\";
    $baseDir = __DIR__ . "/App/";

    if (strpos($class, $prefix) !== 0) {
        return;
    }

    $name = substr($class, strlen($prefix));

    $file = $baseDir . str_replace("\\", "/", $name) . ".php";


    if (file_exists($file)) {
        require_once $file;
    }
});