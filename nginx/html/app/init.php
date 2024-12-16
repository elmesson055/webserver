<?php
// Carregar configurações primeiro
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/functions.php';

// Carregar configuração do banco de dados
$db_config = require_once BASE_PATH . '/config/database.php';

// Função de log personalizada
function custom_log($message, $level = 'info') {
    $log_file = BASE_PATH . '/Logs/app.log';
    $date = date('Y-m-d H:i:s');
    $log_message = "[$date][$level] $message\n";
    error_log($log_message, 3, $log_file);
}

// Conectar ao banco de dados globalmente
try {
    $pdo = connectDB(); // Usando a função do functions.php
    custom_log("Conexão ao banco de dados estabelecida com sucesso");
} catch (PDOException $e) {
    custom_log("Erro ao conectar ao banco de dados: " . $e->getMessage(), 'error');
    die("Erro de conexão com o banco de dados. Por favor, tente novamente mais tarde.");
}

// Funções utilitárias
function formatDate($date, $format = 'd/m/Y') {
    return date($format, strtotime($date));
}

function formatMoney($value) {
    return 'R$ ' . number_format($value, 2, ',', '.');
}

function sanitize($input) {
    if (is_array($input)) {
        foreach($input as $key => $value) {
            $input[$key] = sanitize($value);
        }
    } else {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
    return $input;
}

function validateCSRFToken() {
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
        $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Erro de validação CSRF');
    }
}

// Gerar CSRF token se não existir
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Função para tratamento de erros
function errorHandler($errno, $errstr, $errfile, $errline) {
    custom_log("Erro [$errno]: $errstr em $errfile na linha $errline", 'error');
    return false;
}

// Registrar handler de erros
set_error_handler('errorHandler');
