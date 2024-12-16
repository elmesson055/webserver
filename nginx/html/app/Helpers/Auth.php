<?php

class Auth {
    /**
     * Verifica se o usuário está autenticado
     */
    public static function check() {
        return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
    }

    /**
     * Verifica se o usuário tem uma permissão específica
     */
    public static function hasPermission($permission) {
        global $db;

        if (!self::check()) {
            return false;
        }

        try {
            $userId = $_SESSION['user']['id'];
            
            $stmt = $db->prepare("
                SELECT COUNT(*) 
                FROM role_permissions rp
                JOIN permissions p ON p.id = rp.permission_id
                JOIN user_roles ur ON ur.role_id = rp.role_id
                WHERE ur.user_id = ? AND p.name = ?
            ");
            
            $stmt->execute([$userId, $permission]);
            return $stmt->fetchColumn() > 0;
            
        } catch (PDOException $e) {
            error_log("Erro ao verificar permissão: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica se o usuário tem uma role específica
     */
    public static function hasRole($role) {
        if (!self::check()) {
            return false;
        }

        return isset($_SESSION['user']['role_name']) && $_SESSION['user']['role_name'] === $role;
    }

    /**
     * Retorna todas as permissões do usuário
     */
    public static function getUserPermissions() {
        global $db;

        if (!self::check()) {
            return [];
        }

        try {
            $userId = $_SESSION['user']['id'];
            
            $stmt = $db->prepare("
                SELECT DISTINCT p.name, p.description
                FROM permissions p
                JOIN role_permissions rp ON rp.permission_id = p.id
                JOIN user_roles ur ON ur.role_id = rp.role_id
                WHERE ur.user_id = ?
                ORDER BY p.name
            ");
            
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Erro ao obter permissões do usuário: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Retorna todas as roles do usuário
     */
    public static function getUserRoles() {
        global $db;

        if (!self::check()) {
            return [];
        }

        try {
            $userId = $_SESSION['user']['id'];
            
            $stmt = $db->prepare("
                SELECT r.name, r.description
                FROM roles r
                JOIN user_roles ur ON ur.role_id = r.id
                WHERE ur.user_id = ?
                ORDER BY r.name
            ");
            
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Erro ao obter roles do usuário: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Retorna o usuário atual
     */
    public static function user() {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Retorna o ID do usuário atual
     */
    public static function id() {
        return $_SESSION['user']['id'] ?? null;
    }

    /**
     * Verifica se o usuário tem acesso a um recurso
     * Pode ser uma permissão específica ou uma role
     */
    public static function can($resource) {
        return self::hasPermission($resource) || self::hasRole($resource);
    }

    /**
     * Verifica se o usuário é um administrador
     */
    public static function isAdmin() {
        return self::hasRole('admin');
    }
}
