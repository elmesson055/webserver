<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/database.php';
require_once '../app/Core/Database.php';

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    $username = 'admin_logistica';
    $nova_senha = '123456'; // Substitua pela senha que você quer usar
    
    // Gerar novo hash
    $hash = password_hash($nova_senha, PASSWORD_DEFAULT);
    echo "Novo hash gerado: " . $hash . "<br>";
    
    // Atualizar senha no banco
    $stmt = $db->prepare("
        UPDATE usuarios 
        SET password_hash = :hash 
        WHERE nome_usuario = :username
    ");
    
    $stmt->execute([
        'hash' => $hash,
        'username' => $username
    ]);
    
    if ($stmt->rowCount() > 0) {
        echo "✅ Senha atualizada com sucesso!<br>";
        echo "Agora você pode tentar fazer login com:<br>";
        echo "Usuário: " . $username . "<br>";
        echo "Senha: " . $nova_senha . "<br>";
    } else {
        echo "❌ Nenhum usuário atualizado.";
    }
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
