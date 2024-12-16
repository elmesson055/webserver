<?php
// Inicialização do sistema

// Verificar requisitos do sistema
if (version_compare(PHP_VERSION, '7.4.0', '<')) {
    die('Este sistema requer PHP 7.4 ou superior.');
}

// Verificar extensões necessárias
$required_extensions = ['pdo', 'pdo_mysql', 'mbstring', 'json'];
foreach ($required_extensions as $ext) {
    if (!extension_loaded($ext)) {
        die("Extensão PHP necessária não encontrada: {$ext}");
    }
}

// Configurar handlers de erro
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
        return false;
    }
    
    $error = [
        'type' => $errno,
        'message' => $errstr,
        'file' => $errfile,
        'line' => $errline
    ];
    
    error_log(json_encode($error));
    
    if (DEBUG_MODE) {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
    
    return true;
});

// Configurar handler de exceções não capturadas
set_exception_handler(function($e) {
    error_log($e->getMessage());
    if (DEBUG_MODE) {
        echo "<h1>Erro do Sistema</h1>";
        echo "<p>Mensagem: " . $e->getMessage() . "</p>";
        echo "<p>Arquivo: " . $e->getFile() . "</p>";
        echo "<p>Linha: " . $e->getLine() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    } else {
        echo "Ocorreu um erro no sistema. Por favor, tente novamente mais tarde.";
    }
    exit(1);
});
