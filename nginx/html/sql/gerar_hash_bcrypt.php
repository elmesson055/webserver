<?php
$senha = 'Log@2024#Adm';
$hash = password_hash($senha, PASSWORD_BCRYPT, ['cost' => 10]);
echo $hash;
?>
