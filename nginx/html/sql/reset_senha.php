<?php
// Habilitar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Senha que queremos definir
$senha = 'Log@2024#Adm';

// Gerar hash
$hash = password_hash($senha, PASSWORD_DEFAULT);

// Testar o hash imediatamente
$teste = password_verify($senha, $hash);
echo "Senha original: " . $senha . "\n";
echo "Hash gerado: " . $hash . "\n";
echo "Teste de verificação: " . ($teste ? "FUNCIONA" : "NÃO FUNCIONA") . "\n\n";

if (!$teste) {
    die("ERRO: Hash gerado não está funcionando corretamente!\n");
}

// Gerar SQL para atualizar
echo "Execute este SQL para atualizar a senha:\n\n";
echo "UPDATE usuarios SET password_hash = '" . $hash . "' WHERE nome_usuario = 'admin_logistica';\n";
?>
