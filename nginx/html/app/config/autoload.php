<?php

// Autoload de classes
spl_autoload_register(function ($class) {
    // Converte o namespace para o caminho do arquivo
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    $file = str_replace('App', dirname(__DIR__), $file);
    
    if (file_exists($file)) {
        require $file;
    }
});

// Carrega os helpers globais
require_once dirname(__DIR__) . '/Helpers/PermissionHelper.php';
