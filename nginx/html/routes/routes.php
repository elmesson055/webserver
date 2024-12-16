<?php
// Arquivo de rotas principal

// Definir rotas da aplicação
$routes = [
    '/' => 'app/modules/auth/login.php',
    '/login' => 'app/modules/auth/login.php',
    '/login.php' => 'app/modules/auth/login.php',
    '/logout' => 'app/modules/auth/logout.php',
    '/dashboard' => 'app/modules/dashboard/index.php',
    '/usuarios' => 'app/modules/usuarios/index.php',
    '/configuracoes' => 'app/modules/configuracoes/index.php',
    '/admin' => 'app/modules/admin/index.php',
    '/cadastros' => 'app/modules/cadastros/index.php'
];

// Função para processar rotas
function processRoute($url) {
    global $routes;
    
    // Remove query string se existir
    $url = strtok($url, '?');
    
    // Remove trailing slash se existir
    $url = rtrim($url, '/');
    
    // Se a URL estiver vazia, use '/'
    if (empty($url)) {
        $url = '/';
    }
    
    // Debug
    error_log("Processing route: " . $url);
    
    // Verifica se a rota existe
    if (array_key_exists($url, $routes)) {
        $targetFile = dirname(__DIR__) . '/' . $routes[$url];
        
        // Debug
        error_log("Target file: " . $targetFile);
        
        // Verifica se o arquivo existe
        if (file_exists($targetFile)) {
            require_once $targetFile;
            return;
        }
    }
    
    // Se chegou aqui, a rota não foi encontrada
    header("HTTP/1.0 404 Not Found");
    require_once dirname(__DIR__) . '/app/modules/errors/404.php';
    exit();
}

// Processar a rota atual
$currentUrl = $_SERVER['REQUEST_URI'];
processRoute($currentUrl);
