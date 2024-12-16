<?php

namespace App\Helpers;

class PermissionHelper {
    /**
     * Verifica se o usuário tem uma determinada permissão
     * 
     * @param string $permission Nome da permissão a ser verificada
     * @return bool
     */
    public static function hasPermission($permission) {
        // Por enquanto, vamos considerar que apenas administradores têm acesso
        // Isso será expandido posteriormente com um sistema de permissões mais robusto
        if (!isset($_SESSION['user_role'])) {
            return false;
        }

        // Lista de permissões por perfil
        $rolePermissions = [
            'admin' => [
                'view_users',
                'manage_users',
                'view_roles',
                'manage_roles'
            ],
            'manager' => [
                'view_users'
            ]
        ];

        $userRole = $_SESSION['user_role'];

        // Verifica se o perfil existe e se tem a permissão
        if (isset($rolePermissions[$userRole])) {
            return in_array($permission, $rolePermissions[$userRole]);
        }

        return false;
    }
}

// Função global para facilitar o uso
if (!function_exists('hasPermission')) {
    function hasPermission($permission) {
        return \App\Helpers\PermissionHelper::hasPermission($permission);
    }
}
