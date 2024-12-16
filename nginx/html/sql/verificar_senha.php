<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/database.php';
require_once '../app/Core/Database.php';

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    // Verificar a senha atual no banco
    $stmt = $db->prepare("
        SELECT id, nome_usuario, email, password_hash, status, ativo
        FROM usuarios 
        WHERE nome_usuario = :username
    ");
    
    $stmt->execute(['username' => 'admin_logistica']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<h3>Dados do usuário no banco:</h3>";
    echo "<pre>";
    print_r($user);
    echo "</pre>";
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
