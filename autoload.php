<?php

spl_autoload_register(function (string $class_name): void {
    $namespace_mapping = [
        'App\\' => 'include',
    ];
 
    foreach ($namespace_mapping as $namespace => $directory) {
        if (
            strpos($class_name, $namespace = trim($namespace, '\\')) !== 0
            || (!$directory = realpath(__DIR__ . DIRECTORY_SEPARATOR . trim($directory, DIRECTORY_SEPARATOR)))
        ) {
            continue;
        }
 
        $class_file = $directory . str_replace([$namespace, '\\'], ['', DIRECTORY_SEPARATOR], $class_name) . '.php';
        if (file_exists($class_file)) {
            require_once $class_file;
        }
    }
});

?>