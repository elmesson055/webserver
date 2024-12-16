<?php

namespace App\Core;

class Router {
    private $routes = [];
    private $baseNamespace = 'App\\Modules\\';

    public function __construct() {
        $this->routes = require_once __DIR__ . '/../config/routes.php';
    }

    public function dispatch() {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Remover query string
        $uri = explode('?', $uri)[0];
        
        // Remover trailing slash
        $uri = rtrim($uri, '/');
        
        // Se URI estiver vazia, redirecionar para o painel
        if (empty($uri)) {
            header('Location: /painel');
            exit;
        }

        $route = $this->findRoute($method, $uri);
        
        if ($route) {
            $this->executeRoute($route, $this->extractParams($uri));
        } else {
            $this->handle404();
        }
    }

    private function findRoute($method, $uri) {
        foreach ($this->routes as $pattern => $route) {
            list($routeMethod, $routePattern) = explode('|', $pattern);
            
            if ($routeMethod !== $method) {
                continue;
            }

            $routePattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routePattern);
            
            if (preg_match('#^' . $routePattern . '$#', $uri)) {
                return $route;
            }
        }
        
        return null;
    }

    private function extractParams($uri) {
        $params = [];
        $uriParts = explode('/', trim($uri, '/'));
        
        foreach ($uriParts as $part) {
            if (is_numeric($part)) {
                $params[] = $part;
            }
        }
        
        return $params;
    }

    private function executeRoute($route, $params) {
        $controllerName = $route['controller'];
        $actionName = $route['action'];
        
        // Determinar o módulo baseado no controller
        $module = $this->getModuleFromController($controllerName);
        
        // Construir o namespace completo
        $controllerClass = $this->baseNamespace . $module . '\\Controllers\\' . $controllerName;
        
        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller {$controllerClass} não encontrado");
        }
        
        $controller = new $controllerClass();
        
        if (!method_exists($controller, $actionName)) {
            throw new \Exception("Action {$actionName} não encontrada no controller {$controllerClass}");
        }
        
        call_user_func_array([$controller, $actionName], $params);
    }

    private function getModuleFromController($controller) {
        $modules = [
            'Painel' => 'painel',
            'RegistroInicial' => 'custos_extras',
            'Aprovacoes' => 'custos_extras',
            'Analise' => 'custos_extras',
            'Fornecedor' => 'cadastros',
            'StatusGerais' => 'cadastros',
            'RelatoriosCustos' => 'relatorios',
            'RelatoriosAprovacoes' => 'relatorios',
            'Notificacao' => 'notificacoes',
            'RegrasNotificacao' => 'notificacoes',
            'Auditoria' => 'auditoria'
        ];
        
        foreach ($modules as $prefix => $module) {
            if (strpos($controller, $prefix) === 0) {
                return $module;
            }
        }
        
        throw new \Exception("Não foi possível determinar o módulo para o controller {$controller}");
    }

    public function get($uri, $action, $authRequired = true) {
        $this->routes["GET|{$uri}"] = [
            'controller' => $action,
            'authRequired' => $authRequired,
        ];
    }

    public function post($uri, $action, $authRequired = true) {
        $this->routes["POST|{$uri}"] = [
            'controller' => $action,
            'authRequired' => $authRequired,
        ];
    }

    public function put($uri, $action, $authRequired = true) {
        $this->routes["PUT|{$uri}"] = [
            'controller' => $action,
            'authRequired' => $authRequired,
        ];
    }

    public function delete($uri, $action, $authRequired = true) {
        $this->routes["DELETE|{$uri}"] = [
            'controller' => $action,
            'authRequired' => $authRequired,
        ];
    }

    private function handle404() {
        header("HTTP/1.0 404 Not Found");
        include_once __DIR__ . '/../modules/layouts/404.php';
        exit;
    }
}
