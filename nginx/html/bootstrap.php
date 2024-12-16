<?php
// Iniciar sessão
session_start();

// Carregar configurações
require_once __DIR__ . '/config/config.php';

// Carregar classes autoloader
spl_autoload_register(function ($class) {
    // Converter namespace para caminho de arquivo
    $file = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    
    // Se o arquivo existir, carrega-o
    if (file_exists($file)) {
        require_once $file;
    }
});

// Carregar funções auxiliares na ordem correta
require_once __DIR__ . '/app/functions.php';
require_once __DIR__ . '/app/Core/helpers.php';

// Inicializar componentes do sistema
require_once __DIR__ . '/includes/init.php';
