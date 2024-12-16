<?php
// Função de autoload personalizada
spl_autoload_register(function ($class) {
    // Converter namespace para caminho de arquivo
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/';
    
    // Verificar se a classe usa o namespace App\
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    // Pegar o caminho relativo da classe
    $relative_class = substr($class, $len);
    
    // Converter namespace para caminho de arquivo
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    // Se o arquivo existir, carregá-lo
    if (file_exists($file)) {
        require $file;
    }
});
