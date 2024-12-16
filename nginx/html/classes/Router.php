<?php

class Router {
    private $routes = [];
    private $db;
    
    public function __construct() {
        // Carrega configurações de rotas
        require_once __DIR__ . '/../config/routes_config.php';
        
        // Inicializa conexão com o banco
        try {
            $this->db = new PDO(
                "mysql:host=mysql_custos;dbname=custos_extras;charset=utf8mb4",
                "custos",
                "custo#123",
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            error_log("Erro de conexão com o banco: " . $e->getMessage());
            throw new Exception("Erro ao conectar com o banco de dados");
        }
    }
    
    public function add($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }
    
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Verifica se a rota requer autenticação
        if (requiresAuth($path)) {
            if (!isset($_SESSION['user_id'])) {
                $_SESSION['message'] = "Você precisa estar logado para acessar esta página.";
                $_SESSION['message_type'] = "warning";
                $_SESSION['redirect_after_login'] = $path; // Salva a página para redirecionar depois do login
                header('Location: /login');
                exit;
            }
        }
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $path) {
                $this->executeHandler($route['handler']);
                return;
            }
        }
        
        // Rota não encontrada
        header("HTTP/1.0 404 Not Found");
        require __DIR__ . '/../views/errors/404.php';
    }
    
    private function executeHandler($handler) {
        list($controller, $method) = explode('@', $handler);
        
        $controllerFile = __DIR__ . "/../controllers/{$controller}.php";
        if (!file_exists($controllerFile)) {
            throw new Exception("Controller não encontrado: {$controller}");
        }
        
        require_once $controllerFile;
        
        // Instancia o controller passando a conexão com o banco
        $controllerInstance = new $controller($this->db);
        
        if (!method_exists($controllerInstance, $method)) {
            throw new Exception("Método não encontrado: {$method}");
        }
        
        return $controllerInstance->$method();
    }
}
