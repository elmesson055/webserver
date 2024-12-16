<?php

// Funções globais não devem ter namespace

/**
 * Verifica se o usuário está autenticado
 * @return bool
 */
function isAuthenticated(): bool {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Requer que o usuário esteja autenticado
 * Se não estiver, redireciona para a página de login
 * @return void
 */
function requireAuth(): void {
    if (!isAuthenticated()) {
        $_SESSION['message'] = "Você precisa fazer login para acessar esta página.";
        $_SESSION['message_type'] = "warning";
        $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'];
        header("Location: /login");
        exit;
    }
}

/**
 * Verifica se o usuário tem uma determinada permissão
 * @param string $permission
 * @return bool
 */
function hasPermission(string $permission): bool {
    if (!isAuthenticated()) {
        return false;
    }

    $user = getCurrentUser();
    if (!$user) {
        return false;
    }

    // Se for admin, tem todas as permissões
    if (isAdmin()) {
        return true;
    }

    // Verifica se o usuário tem a permissão específica
    $permissions = $_SESSION['user_permissions'] ?? [];
    return in_array($permission, $permissions);
}

/**
 * Requer que o usuário tenha uma determinada permissão
 * Se não tiver, redireciona para a página anterior
 * @param string $permission
 * @return void
 */
function requirePermission(string $permission): void {
    if (!hasPermission($permission)) {
        $_SESSION['message'] = "Você não tem permissão para acessar esta página.";
        $_SESSION['message_type'] = "danger";
        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/dashboard'));
        exit;
    }
}

/**
 * Busca as funções/papéis disponíveis no sistema
 * @return array
 */
function getRoles(): array {
    $roleModel = new \App\Models\Role();
    return $roleModel->getAll();
}

/**
 * Busca um usuário pelo ID
 * @param int $id
 * @return array|null
 */
function getUserById(int $id): ?array {
    $userModel = new \App\Models\User();
    return $userModel->find($id);
}

/**
 * Retorna o usuário atualmente autenticado
 * @return array|null
 */
function getCurrentUser(): ?array {
    if (!isAuthenticated()) {
        return null;
    }
    return getUserById($_SESSION['user_id']);
}

/**
 * Verifica se o usuário atual é admin
 * @return bool
 */
function isAdmin(): bool {
    if (!isAuthenticated()) {
        return false;
    }
    return $_SESSION['user_role'] === 'admin';
}

/**
 * Retorna os departamentos disponíveis no sistema
 * @return array
 */
function getDepartments(): array {
    return [
        'comercial' => 'Comercial',
        'financeiro' => 'Financeiro',
        'operacional' => 'Operacional',
        'rh' => 'Recursos Humanos',
        'ti' => 'Tecnologia da Informação'
    ];
}

/**
 * Formata uma data para o formato brasileiro
 * @param string $date Data no formato Y-m-d ou Y-m-d H:i:s
 * @return string Data formatada
 */
function formatDate(string $date): string {
    if (empty($date)) return '';
    
    $timestamp = strtotime($date);
    if (strpos($date, ':') !== false) {
        return date('d/m/Y H:i:s', $timestamp);
    }
    return date('d/m/Y', $timestamp);
}

/**
 * Formata um valor monetário para o formato brasileiro
 * @param float $value Valor a ser formatado
 * @return string Valor formatado
 */
function formatMoney(float $value): string {
    return 'R$ ' . number_format($value, 2, ',', '.');
}

/**
 * Gera um token CSRF
 * @return string
 */
function generateCsrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verifica se o token CSRF é válido
 * @param string $token
 * @return bool
 */
function validateCsrfToken(string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Retorna a URL base da aplicação
 * @return string
 */
function baseUrl(): string {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    return $protocol . '://' . $_SERVER['HTTP_HOST'];
}

/**
 * Retorna a URL atual
 * @return string
 */
function currentUrl(): string {
    return baseUrl() . $_SERVER['REQUEST_URI'];
}

/**
 * Retorna o status de um custo extra de forma formatada
 * @param string $status
 * @return string
 */
function formatStatus(string $status): string {
    $statusMap = [
        'pendente' => '<span class="badge bg-warning">Pendente</span>',
        'em_aprovacao' => '<span class="badge bg-info">Em Aprovação</span>',
        'aprovado' => '<span class="badge bg-success">Aprovado</span>',
        'reprovado' => '<span class="badge bg-danger">Reprovado</span>'
    ];
    
    return $statusMap[$status] ?? '<span class="badge bg-secondary">Desconhecido</span>';
}

/**
 * Log personalizado
 * @param string $message
 * @param string $level
 * @return void
 */
function custom_log(string $message, string $level = 'info'): void {
    if (!defined('LOG_PATH')) {
        return;
    }

    $date = date('Y-m-d H:i:s');
    $logMessage = "[$date][$level] $message" . PHP_EOL;
    $logFile = LOG_PATH . '/' . date('Y-m-d') . '.log';

    error_log($logMessage, 3, $logFile);
}
