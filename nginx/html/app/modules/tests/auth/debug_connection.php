<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== Teste de Conexão com Banco de Dados ===\n\n";

// 1. Verificar se o MySQL está rodando
echo "1. Verificando serviço MySQL...\n";
$mysql_running = false;
try {
    $socket = @fsockopen('localhost', 3306);
    if ($socket) {
        $mysql_running = true;
        fclose($socket);
        echo "✓ MySQL está rodando na porta 3306\n";
    } else {
        echo "✗ MySQL não está acessível na porta 3306\n";
    }
} catch (Exception $e) {
    echo "✗ Erro ao verificar MySQL: " . $e->getMessage() . "\n";
}

echo "\n2. Carregando configurações...\n";
$config_file = dirname(dirname(dirname(dirname(__FILE__)))) . '/config/database.php';
echo "Arquivo de configuração: $config_file\n";

if (file_exists($config_file)) {
    echo "✓ Arquivo de configuração encontrado\n";
    require_once $config_file;
} else {
    echo "✗ Arquivo de configuração não encontrado\n";
    exit(1);
}

// 3. Verificar constantes
echo "\n3. Verificando constantes de configuração:\n";
$constants = ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'DB_PORT', 'DB_CHARSET'];
foreach ($constants as $constant) {
    if (defined($constant)) {
        echo "✓ $constant = " . ($constant === 'DB_PASS' ? '********' : constant($constant)) . "\n";
    } else {
        echo "✗ $constant não está definida\n";
    }
}

// 4. Tentar conexão sem banco de dados primeiro
echo "\n4. Tentando conexão básica (sem banco de dados)...\n";
try {
    $basic_dsn = sprintf(
        'mysql:host=%s;port=%s',
        DB_HOST,
        DB_PORT
    );
    $pdo = new PDO($basic_dsn, DB_USER, DB_PASS);
    echo "✓ Conexão básica bem sucedida\n";
    
    // Verificar se o banco existe
    echo "\n5. Verificando banco de dados...\n";
    $stmt = $pdo->query("SHOW DATABASES LIKE '" . DB_NAME . "'");
    $database_exists = $stmt->rowCount() > 0;
    
    if ($database_exists) {
        echo "✓ Banco de dados '" . DB_NAME . "' existe\n";
        
        // Tentar selecionar o banco
        $pdo->query("USE " . DB_NAME);
        echo "✓ Banco de dados selecionado com sucesso\n";
        
        // Verificar tabelas
        echo "\n6. Verificando tabelas...\n";
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (empty($tables)) {
            echo "! Nenhuma tabela encontrada no banco de dados\n";
        } else {
            echo "Tabelas encontradas:\n";
            foreach ($tables as $table) {
                echo "- $table\n";
            }
        }
        
    } else {
        echo "✗ Banco de dados '" . DB_NAME . "' não existe\n";
    }
    
} catch (PDOException $e) {
    echo "✗ Erro na conexão: " . $e->getMessage() . "\n";
    echo "Código do erro: " . $e->getCode() . "\n";
    
    // Códigos de erro comuns
    $error_codes = [
        1045 => "Acesso negado para o usuário (senha incorreta ou usuário não existe)",
        1049 => "Banco de dados não existe",
        2002 => "MySQL não está rodando ou não está acessível",
        2003 => "Servidor não encontrado",
        2005 => "Host desconhecido",
        2006 => "Servidor MySQL foi embora",
        2013 => "Conexão perdida com MySQL durante a query"
    ];
    
    if (isset($error_codes[$e->getCode()])) {
        echo "\nDiagnóstico: " . $error_codes[$e->getCode()] . "\n";
    }
}
