<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Credenciais
$username = 'admin_logistica';
$password = 'Log@2024#Adm';

echo "=== Simulação de Login ===\n";
echo "Usuário: $username\n";
echo "Senha: $password\n\n";

try {
    // Conectar ao banco
    $db = new PDO(
        'mysql:host=localhost;dbname=logistica_transportes',
        'logistica_admin',
        'Log@2024#Adm'
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Buscar usuário
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
    
    echo "Resultado da busca:\n";
    var_dump($user);
    
    if (!$user) {
        die("Usuário não encontrado ou inativo.\n");
    }
    
    echo "\nTestando senha:\n";
    echo "Hash armazenado: " . $user['password_hash'] . "\n";
    
    $verificacao = password_verify($password, $user['password_hash']);
    echo "Resultado da verificação: " . ($verificacao ? "SENHA CORRETA" : "SENHA INCORRETA") . "\n";
    
    if (!$verificacao) {
        // Testar gerando um novo hash
        $novo_hash = password_hash($password, PASSWORD_DEFAULT);
        echo "\nGerando novo hash para comparação:\n";
        echo "Novo hash: $novo_hash\n";
        echo "Teste com novo hash: " . (password_verify($password, $novo_hash) ? "FUNCIONA" : "NÃO FUNCIONA") . "\n";
    }
    
} catch (Exception $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
?>
