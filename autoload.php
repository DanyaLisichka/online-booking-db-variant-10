<?php

spl_autoload_register(function ($class) {

    $paths = [
        __DIR__ . '/src/',
        __DIR__ . '/controllers/',
        __DIR__ . '/helpers/'
    ];

    foreach ($paths as $path) {

        $file = $path . $class . '.php';

        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});