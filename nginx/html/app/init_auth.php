<?php
// Carregar configurações primeiro
require_once __DIR__ . '/../config/config.php';

// Carregar configuração do banco de dados
$db_config = require_once BASE_PATH . '/config/database.php';

// Função de log personalizada
function custom_log($message, $level = 'info') {
    $log_file = BASE_PATH . '/Logs/app.log';
    $date = date('Y-m-d H:i:s');
    $log_message = "[$date][$level] $message\n";
    error_log($log_message, 3, $log_file);
}

// Conectar ao banco de dados com diagnóstico detalhado
function connectDB() {
    global $db_config;
    
    try {
        custom_log("Iniciando tentativa de conexão ao banco de dados");
        $dsn = "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset={$db_config['charset']}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        $pdo = new PDO($dsn, $db_config['username'], $db_config['password'], $options);
        custom_log("Conexão ao banco de dados estabelecida com sucesso");
        return $pdo;
    } catch (PDOException $e) {
        custom_log("Erro ao conectar ao banco de dados: " . $e->getMessage(), 'error');
        throw $e;
    }
}

// Funções utilitárias
function sanitize($input) {
    if (is_array($input)) {
        foreach ($input as $key => $value) {
            $input[$key] = sanitize($value);
        }
    } else {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
    return $input;
}

// Gerar CSRF token se não existir
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Função para tratamento de erros
function errorHandler($errno, $errstr, $errfile, $errline) {
    custom_log("Erro: [$errno] $errstr em $errfile na linha $errline", 'error');
    return true;
}

// Registrar handler de erros
set_error_handler('errorHandler');
