<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== Teste de Caminhos ===\n\n";

$arquivo_atual = __FILE__;
echo "Arquivo atual: " . $arquivo_atual . "\n";

$caminhos = [
    'config.php' => dirname(dirname(__FILE__)) . '/config/config.php',
    'database.php' => dirname(dirname(__FILE__)) . '/config/database.php',
    'Database.php' => dirname(dirname(__FILE__)) . '/app/Core/Database.php',
    'functions.php' => dirname(dirname(__FILE__)) . '/app/functions.php'
];

foreach ($caminhos as $nome => $caminho) {
    echo "\nTestando arquivo: " . $nome . "\n";
    echo "Caminho completo: " . $caminho . "\n";
    echo "Arquivo existe? " . (file_exists($caminho) ? "SIM" : "NÃO") . "\n";
    
    if (file_exists($caminho)) {
        echo "É legível? " . (is_readable($caminho) ? "SIM" : "NÃO") . "\n";
        
        if (is_readable($caminho)) {
            echo "Primeiras linhas do arquivo:\n";
            $linhas = array_slice(file($caminho), 0, 3);
            foreach ($linhas as $linha) {
                echo trim($linha) . "\n";
            }
        }
    }
}

// Tentar carregar os arquivos
echo "\n=== Tentando carregar os arquivos ===\n";
foreach ($caminhos as $nome => $caminho) {
    echo "\nCarregando " . $nome . "...\n";
    try {
        if (file_exists($caminho)) {
            require_once $caminho;
            echo "Carregado com sucesso!\n";
        } else {
            echo "ERRO: Arquivo não existe\n";
        }
    } catch (Exception $e) {
        echo "ERRO ao carregar: " . $e->getMessage() . "\n";
    }
}
?>
