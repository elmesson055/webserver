<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$senha = 'Log@2024#Adm';
$hash = password_hash($senha, PASSWORD_DEFAULT);

echo "Senha original: $senha\n";
echo "Hash gerado: $hash\n";

// Conectar ao banco
$db = new PDO(
    'mysql:host=localhost;dbname=logistica_transportes',
    'logistica_admin',
    'Log@2024#Adm'
);
$stmt = $db->query("SELECT password_hash FROM usuarios WHERE nome_usuario = 'admin_logistica'");
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo "\nHash armazenado no banco: " . $user['password_hash'] . "\n";

// Testar verificação
$verificacao = password_verify($senha, $user['password_hash']);
echo "\nVerificação da senha: " . ($verificacao ? "CORRETA" : "INCORRETA") . "\n";

// Atualizar com novo hash
$stmt = $db->prepare("UPDATE usuarios SET password_hash = ? WHERE nome_usuario = 'admin_logistica'");
$stmt->execute([$hash]);

echo "\nSenha atualizada no banco. Tente fazer login com:\n";
echo "Usuário: admin_logistica\n";
echo "Senha: $senha\n";
?>
