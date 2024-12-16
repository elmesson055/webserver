<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/database.php';
require_once '../app/Core/Database.php';

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    // Dados de teste
    $username = 'admin_logistica';
    $password = '123456'; // Senha atualizada
    
    echo "<h2>Teste de Login</h2>";
    
    // Passo 1: Buscar usuário
    echo "<h3>Passo 1: Buscar usuário</h3>";
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
    
    if ($user) {
        echo "✅ Usuário encontrado:<br>";
        echo "ID: " . $user['id'] . "<br>";
        echo "Nome: " . $user['nome'] . "<br>";
        echo "Status: " . $user['status'] . "<br>";
        echo "Ativo: " . $user['ativo'] . "<br>";
        echo "Hash atual: " . $user['password_hash'] . "<br>";
        
        // Passo 2: Verificar senha
        echo "<h3>Passo 2: Verificar senha</h3>";
        if (password_verify($password, $user['password_hash'])) {
            echo "✅ Senha correta!<br>";
            
            // Passo 3: Simular criação da sessão
            echo "<h3>Passo 3: Simular sessão</h3>";
            $session_data = [
                'id' => $user['id'],
                'nome_usuario' => $user['nome_usuario'],
                'email' => $user['email'],
                'funcao_id' => $user['funcao_id']
            ];
            echo "Dados da sessão que seriam criados:<br>";
            echo "<pre>";
            print_r($session_data);
            echo "</pre>";
            
        } else {
            echo "❌ Senha incorreta!<br>";
            echo "Hash armazenado: " . $user['password_hash'] . "<br>";
            echo "Senha fornecida: " . $password . "<br>";
        }
    } else {
        echo "❌ Usuário não encontrado<br>";
        
        // Mostrar todos os usuários para debug
        echo "<h3>Usuários na tabela:</h3>";
        $allUsers = $db->query("SELECT id, nome_usuario, email, status, ativo FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);
        echo "<pre>";
        print_r($allUsers);
        echo "</pre>";
    }
    
} catch (Exception $e) {
    echo "<h3>❌ Erro:</h3>";
    echo $e->getMessage();
}
