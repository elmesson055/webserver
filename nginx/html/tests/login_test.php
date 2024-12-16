<?php

// Simulando um ambiente de teste para o login
session_start();

// Array de usuários para testar
$users_to_test = [
    ['username' => 'admin', 'password' => 'admin123'],
    ['username' => 'admin_novo', 'password' => 'admin123'],
    ['username' => 'admin_sistema', 'password' => 'admin123']
];

foreach ($users_to_test as $user) {
    echo "\nTestando login com usuário: {$user['username']}\n";
    echo "----------------------------------------\n";

    // Mock de dados de entrada
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_POST['username'] = $user['username'];
    $_POST['password'] = $user['password'];

    // Incluindo o arquivo de login para testar
    require_once '/var/www/html/login.php';

    // Verificando se o login foi bem-sucedido
    if (isset($_SESSION['user_id'])) {
        echo "Login bem-sucedido para o usuário: {$_SESSION['username']}\n";
        // Limpar a sessão para o próximo teste
        session_destroy();
        session_start();
    } else {
        echo "Falha no login. Mensagem: " . ($error ?? 'Erro desconhecido') . "\n";
    }
    echo "----------------------------------------\n";
}

// Função para simular redirecionamento
function redirect($url) {
    echo "Redirecionando para: $url\n";
}

// Função para simular o log
function custom_log($message, $type) {
    echo "[$type] $message\n";
}

?>
