<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Teste de Conexão Direto</h2>";

try {
    // Dados de conexão
    $host = 'localhost';
    $dbname = 'logistica_transportes';
    $user = 'logistica_admin';
    $pass = 'LOg1st1ca2024!';
    $port = '3306';
    
    echo "<p>Tentando conectar diretamente com PDO...</p>";
    
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color:green'>Conexão PDO estabelecida com sucesso!</p>";
    
    // Testar se o usuário tem permissão para SELECT
    echo "<p>Testando permissão de SELECT...</p>";
    $stmt = $pdo->query("SELECT COUNT(*) FROM usuarios");
    $count = $stmt->fetchColumn();
    echo "<p style='color:green'>Permissão de SELECT OK! Total de usuários: $count</p>";
    
    // Testar se o usuário tem permissão para INSERT
    echo "<p>Testando permissão de INSERT...</p>";
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("INSERT INTO tentativas_login (usuario_id, data_tentativa, sucesso) VALUES (1, NOW(), 0)");
    $stmt->execute();
    $pdo->rollBack();
    echo "<p style='color:green'>Permissão de INSERT OK!</p>";
    
} catch (PDOException $e) {
    echo "<p style='color:red'>ERRO PDO: " . $e->getMessage() . "</p>";
    echo "<p>Código do erro: " . $e->getCode() . "</p>";
    
    // Tentar conexão alternativa com mysqli
    echo "<hr><p>Tentando conexão alternativa com mysqli...</p>";
    try {
        $mysqli = new mysqli($host, $user, $pass, $dbname, $port);
        if ($mysqli->connect_errno) {
            throw new Exception("Erro mysqli: " . $mysqli->connect_error);
        }
        echo "<p style='color:green'>Conexão mysqli estabelecida com sucesso!</p>";
        $mysqli->close();
    } catch (Exception $e) {
        echo "<p style='color:red'>ERRO mysqli: " . $e->getMessage() . "</p>";
    }
}
