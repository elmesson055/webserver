<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/database.php';
require_once '../app/Core/Database.php';

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    // Teste com o usuário admin_logistica
    $username = 'admin_logistica';
    
    $stmt = $db->prepare("
        SELECT * FROM usuarios 
        WHERE (email = :email OR nome_usuario = :username)
        AND status = 'ativo'
    ");
    
    $stmt->execute([
        'email' => $username,
        'username' => $username
    ]);
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<pre>";
    if ($user) {
        echo "Usuário encontrado:\n";
        print_r($user);
    } else {
        echo "Usuário não encontrado\n";
        
        // Verificar todos os usuários na tabela
        $allUsers = $db->query("SELECT * FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);
        echo "\nTodos os usuários na tabela:\n";
        print_r($allUsers);
    }
    echo "</pre>";
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
