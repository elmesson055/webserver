<?php

// Rotas que requerem autenticação
$protected_routes = [
    '/profile',
    '/profile/change-password',
    '/users',
    '/costs',
    '/reports'
];

// Rotas públicas (não requerem autenticação)
$public_routes = [
    '/',
    '/login',
    '/auth/login',
    '/auth/forgot-password',
    '/auth/reset-password',
    '/logout'
];

// Função para verificar se a rota atual requer autenticação
function requiresAuth($current_path) {
    global $protected_routes;
    
    // Garantir que $protected_routes é um array
    if (!is_array($protected_routes)) {
        return false;
    }
    
    foreach ($protected_routes as $route) {
        if (strpos($current_path, $route) === 0) {
            return true;
        }
    }
    
    return false;
}

// Função para verificar se é uma rota pública
function isPublicRoute($current_path) {
    global $public_routes;
    
    // Garantir que $public_routes é um array
    if (!is_array($public_routes)) {
        return false;
    }
    
    return in_array($current_path, $public_routes);
}
