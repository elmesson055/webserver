<?php
$password = 'Log@2024#Adm';
$hash = password_hash($password, PASSWORD_BCRYPT);
echo "Senha original: " . $password . "\n";
echo "Novo hash: " . $hash . "\n";
echo "Verificação: " . (password_verify($password, $hash) ? "Hash válido" : "Hash inválido") . "\n";
