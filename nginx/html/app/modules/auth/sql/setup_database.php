<?php
require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/app/config/config.php';

try {
    // Conectar ao MySQL
    $pdo = new PDO(
        "mysql:host={$db_config['host']};port={$db_config['port']};charset={$db_config['charset']}",
        $db_config['username'],
        $db_config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    echo "Conectado ao MySQL com sucesso!\n";
    
    // Criar banco de dados se não existir
    $pdo->exec("CREATE DATABASE IF NOT EXISTS {$db_config['dbname']}");
    echo "Banco de dados {$db_config['dbname']} verificado/criado com sucesso!\n";
    
    // Selecionar o banco de dados
    $pdo->exec("USE {$db_config['dbname']}");
    echo "Usando banco de dados {$db_config['dbname']}\n";
    
    // Ler e executar os arquivos SQL
    $sqlFiles = [
        __DIR__ . '/create_tables.sql',
        __DIR__ . '/create_test_user.sql'
    ];
    
    foreach ($sqlFiles as $file) {
        if (file_exists($file)) {
            $sql = file_get_contents($file);
            echo "\nExecutando arquivo: " . basename($file) . "\n";
            
            // Dividir o arquivo em comandos individuais
            $commands = array_filter(
                array_map(
                    'trim',
                    explode(';', $sql)
                )
            );
            
            // Executar cada comando
            foreach ($commands as $command) {
                if (!empty($command)) {
                    $pdo->exec($command);
                    echo ".";
                }
            }
            echo "\nArquivo executado com sucesso!\n";
        } else {
            echo "\nArquivo não encontrado: " . basename($file) . "\n";
        }
    }
    
    echo "\nTodas as operações foram concluídas com sucesso!\n";
    
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage() . "\n";
    exit(1);
}
