<?php
// Definir caminho base
define('BASE_PATH', 'C:/webserver/nginx/html');

// Carregar configurações
require_once BASE_PATH . '/config/config.php';

// Redirecionar para a view correta
header('Location: views/login.php');
exit();