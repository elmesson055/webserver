<?php
$senha = 'Log@2024#Adm';
$hash = password_hash($senha, PASSWORD_DEFAULT);
echo "Hash gerado para a senha: " . $hash;
