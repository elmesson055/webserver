<?php

namespace App\Middleware;

use App\Core\Middleware;
use App\Exceptions\AuthorizationException;

class RoleMiddleware extends Middleware {
    private $requiredRole;

    public function __construct($role) {
        $this->requiredRole = $role;
    }

    public function handle($params) {
        if (!isset($_SESSION['user_role'])) {
            throw new AuthorizationException('Usuário não tem permissão para acessar este recurso.');
        }

        if ($_SESSION['user_role'] !== $this->requiredRole && $_SESSION['user_role'] !== 'admin') {
            throw new AuthorizationException('Usuário não tem permissão para acessar este recurso.');
        }

        return true;
    }
}
