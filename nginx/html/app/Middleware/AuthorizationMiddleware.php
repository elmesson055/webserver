<?php
namespace App\Middleware;

use PDO;
use Exception;

class AuthorizationMiddleware {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function checkPermission($userId, $requiredPermission) {
        // Log de depuração
        error_log("Verificando permissão para usuário $userId. Permissão necessária: $requiredPermission");
        
        try {
            // Buscar permissões do papel do usuário
            $query = "
                SELECT DISTINCT p.name
                FROM permissions p
                INNER JOIN role_permissions rp ON p.id = rp.permission_id
                INNER JOIN users u ON u.role_id = rp.role_id
                WHERE u.id = :user_id AND p.active = 1
                UNION
                SELECT DISTINCT p.name
                FROM permissions p
                INNER JOIN user_permissions up ON p.id = up.permission_id
                WHERE up.user_id = :user_id AND p.active = 1
            ";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute(['user_id' => $userId]);
            $permissions = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Log de permissões do usuário
            error_log("Permissões do usuário: " . implode(', ', $permissions));
            
            // Verificar se o usuário tem a permissão necessária
            $hasPermission = in_array($requiredPermission, $permissions);
            
            error_log($hasPermission 
                ? "Usuário tem permissão" 
                : "Usuário NÃO tem permissão");
            
            return $hasPermission;
        } catch (Exception $e) {
            error_log("Erro ao verificar permissões: " . $e->getMessage());
            return false;
        }
    }

    public function restrictAccess($requiredPermission) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Verificar se usuário está logado
        if (!isset($_SESSION['user_id'])) {
            error_log("Tentativa de acesso sem login");
            throw new Exception("Usuário não autenticado");
        }

        // Log detalhado de verificação de permissão
        error_log("Verificando acesso. Permissão necessária: " . $requiredPermission);
        error_log("Permissões do usuário: " . json_encode($_SESSION['user']['permissions'] ?? []));
        
        // Verificação de permissão existente
        $userPermissions = $_SESSION['user']['permissions'] ?? [];
        
        if (!$this->checkPermission($_SESSION['user_id'], $requiredPermission)) {
            error_log("Acesso negado. Usuário não possui a permissão: " . $requiredPermission);
            throw new Exception("Acesso negado. Permissão necessária: " . $requiredPermission);
        }
        
        error_log("Acesso concedido para a permissão: " . $requiredPermission);
    }

    public function filterMenuItems($menuItems, $userId) {
        error_log("Filtrando menu para usuário: $userId");
        
        try {
            // Buscar todas as permissões do usuário (do papel e individuais)
            $query = "
                SELECT DISTINCT p.name
                FROM permissions p
                INNER JOIN role_permissions rp ON p.id = rp.permission_id
                INNER JOIN users u ON u.role_id = rp.role_id
                WHERE u.id = :user_id AND p.active = 1
                UNION
                SELECT DISTINCT p.name
                FROM permissions p
                INNER JOIN user_permissions up ON p.id = up.permission_id
                WHERE up.user_id = :user_id AND p.active = 1
            ";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute(['user_id' => $userId]);
            $userPermissions = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            error_log("Permissões do usuário: " . implode(', ', $userPermissions));
            
            // Filtrar itens do menu baseado nas permissões
            $filteredItems = array_filter($menuItems, function($item) use ($userPermissions) {
                // Se o item não requer permissão, mostrar
                if (!isset($item['permission']) || empty($item['permission'])) {
                    return true;
                }
                
                // Verificar se o usuário tem a permissão necessária
                return in_array($item['permission'], $userPermissions);
            });
            
            error_log("Itens de menu filtrados: " . json_encode(array_values($filteredItems)));
            
            return array_values($filteredItems);
        } catch (Exception $e) {
            error_log("Erro ao filtrar menu: " . $e->getMessage());
            return [];
        }
    }
}
