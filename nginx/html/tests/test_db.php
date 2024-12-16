<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Teste de Conexão com o Banco de Dados</h2>";

try {
    // Dados de conexão
    $host = 'localhost';
    $dbname = 'logistica_transportes';
    $user = 'logistica_admin';
    $pass = 'LOg1st1ca2024!';
    $port = '3306';
    
    echo "<p>Tentando conectar com:</p>";
    echo "<ul>";
    echo "<li>Host: $host</li>";
    echo "<li>Database: $dbname</li>";
    echo "<li>User: $user</li>";
    echo "<li>Port: $port</li>";
    echo "</ul>";
    
    // Tentar conexão direta
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ];
    
    echo "<p>Tentando conexão...</p>";
    
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    echo "<p style='color:green'>Conexão estabelecida com sucesso!</p>";
    
    // Testar uma query simples
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<p>Tabelas encontradas:</p>";
    if (empty($tables)) {
        echo "<p style='color:orange'>Nenhuma tabela encontrada no banco de dados!</p>";
    } else {
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";
    }
    
    // Testar se a tabela usuarios existe
    if (in_array('usuarios', $tables)) {
        echo "<p>Testando tabela 'usuarios':</p>";
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios");
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<p>Total de usuários: " . $count['total'] . "</p>";
        
        // Mostrar estrutura da tabela
        echo "<p>Estrutura da tabela 'usuarios':</p>";
        $stmt = $pdo->query("DESCRIBE usuarios");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<table border='1'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Chave</th><th>Default</th></tr>";
        foreach ($columns as $column) {
            echo "<tr>";
            echo "<td>" . $column['Field'] . "</td>";
            echo "<td>" . $column['Type'] . "</td>";
            echo "<td>" . $column['Null'] . "</td>";
            echo "<td>" . $column['Key'] . "</td>";
            echo "<td>" . $column['Default'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color:red'>A tabela 'usuarios' não existe!</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color:red'>Erro de conexão: " . $e->getMessage() . "</p>";
    echo "<p>Código do erro: " . $e->getCode() . "</p>";
}
