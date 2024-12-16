<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Carregar configurações
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config/session.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config/database.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config/config.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/app/Core/Database.php';
require_once dirname(dirname(dirname(__FILE__))) . '/auth/models/UserModel.php';
require_once dirname(dirname(dirname(__FILE__))) . '/auth/controllers/AuthController.php';

use App\Modules\Auth\Controllers\AuthController;

try {
    echo "=== Teste do Sistema de Autenticação ===\n\n";
    
    echo "1. Verificando configurações do banco de dados:\n";
    echo "Host: " . DB_HOST . "\n";
    echo "Database: " . DB_NAME . "\n";
    echo "User: " . DB_USER . "\n";
    echo "Port: " . DB_PORT . "\n\n";
    
    echo "2. Inicializando AuthController...\n";
    $auth = new AuthController();
    echo "AuthController inicializado com sucesso!\n\n";
    
    echo "3. Tentando fazer login com credenciais de teste:\n";
    $result = $auth->login('admin', 'admin123');
    echo "Resultado do login: \n";
    print_r($result);
    
} catch (Exception $e) {
    echo "\nErro durante o teste: " . $e->getMessage() . "\n";
    echo "Arquivo: " . $e->getFile() . "\n";
    echo "Linha: " . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
