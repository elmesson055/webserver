<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configurações diretas para teste
$db_host = 'localhost';
$db_name = 'logistica_transportes';
$db_user = 'logistica_admin';
$db_pass = 'LOg1st1ca2024!';
$db_port = '3306';
$db_charset = 'utf8mb4';

echo "=== Teste Direto de Conexão MySQL ===\n\n";

try {
    // Primeiro tentar conexão sem especificar banco de dados
    echo "1. Tentando conexão básica...\n";
    $dsn = "mysql:host=$db_host;port=$db_port;charset=$db_charset";
    echo "DSN: $dsn\n";
    echo "Usuário: $db_user\n";
    
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $db_charset"
    ];
    
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
    echo "✓ Conexão básica estabelecida!\n\n";

    // Verificar se o banco existe
    echo "2. Verificando banco de dados '$db_name'...\n";
    $stmt = $pdo->query("SHOW DATABASES LIKE '$db_name'");
    if ($stmt->rowCount() > 0) {
        echo "✓ Banco de dados existe!\n\n";
        
        // Conectar ao banco específico
        echo "3. Conectando ao banco '$db_name'...\n";
        $pdo->exec("USE `$db_name`");
        echo "✓ Banco selecionado com sucesso!\n\n";
        
        // Listar tabelas
        echo "4. Listando tabelas...\n";
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        if (empty($tables)) {
            echo "! Nenhuma tabela encontrada\n";
        } else {
            echo "Tabelas encontradas:\n";
            foreach ($tables as $table) {
                echo "- $table\n";
            }
        }
    } else {
        echo "✗ Banco de dados não existe!\n";
        echo "Criando banco de dados...\n";
        $pdo->exec("CREATE DATABASE `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "✓ Banco de dados criado com sucesso!\n";
    }

} catch (PDOException $e) {
    echo "\n✗ ERRO: " . $e->getMessage() . "\n";
    echo "Código: " . $e->getCode() . "\n\n";
    
    switch ($e->getCode()) {
        case 1045:
            echo "Diagnóstico: Usuário ou senha incorretos\n";
            echo "Soluções:\n";
            echo "1. Verifique se o usuário '$db_user' existe no MySQL\n";
            echo "2. Verifique se a senha está correta\n";
            echo "3. Execute no MySQL como root:\n";
            echo "   CREATE USER '$db_user'@'localhost' IDENTIFIED BY '$db_pass';\n";
            echo "   GRANT ALL PRIVILEGES ON $db_name.* TO '$db_user'@'localhost';\n";
            echo "   FLUSH PRIVILEGES;\n";
            break;
        case 1049:
            echo "Diagnóstico: Banco de dados não existe\n";
            break;
        case 2002:
            echo "Diagnóstico: MySQL não está rodando ou não está acessível\n";
            break;
        default:
            echo "Diagnóstico: Erro desconhecido\n";
    }
}
