<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configurações do banco
require_once dirname(dirname(__FILE__)) . '/config/database.php';
require_once dirname(dirname(__FILE__)) . '/app/Core/Database.php';

use App\Core\Database;

try {
    // Conectar ao banco
    $db = Database::getInstance()->getConnection();
    
    // Buscar usuário
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE nome_usuario = ?");
    $stmt->execute(['admin_logistica']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        die("Usuário não encontrado!\n");
    }
    
    // Informações do usuário
    echo "ID: " . $user['id'] . "\n";
    echo "Nome de usuário: " . $user['nome_usuario'] . "\n";
    echo "Hash atual: " . $user['password_hash'] . "\n";
    
    // Testar senha
    $senha = 'Log@2024#Adm';
    echo "\nTestando senha: " . $senha . "\n";
    
    // Gerar novo hash
    $novo_hash = password_hash($senha, PASSWORD_DEFAULT);
    echo "Novo hash gerado: " . $novo_hash . "\n";
    
    // Verificar senha atual
    $verificacao = password_verify($senha, $user['password_hash']);
    echo "Verificação com hash atual: " . ($verificacao ? "CORRETA" : "INCORRETA") . "\n";
    
    // Verificar com novo hash
    $verificacao_novo = password_verify($senha, $novo_hash);
    echo "Verificação com novo hash: " . ($verificacao_novo ? "CORRETA" : "INCORRETA") . "\n";
    
    // Atualizar senha
    $stmt = $db->prepare("UPDATE usuarios SET password_hash = ? WHERE nome_usuario = ?");
    $stmt->execute([$novo_hash, 'admin_logistica']);
    echo "\nSenha atualizada com novo hash!\n";
    
} catch (Exception $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
}
?>
