<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'department',
        'role_id',
        'active',
        'created_by',
        'updated_by',
        'last_login',
        'password_reset_token',
        'password_reset_expires_at'
    ];

    /**
     * Busca todos os usuários com filtros
     */
    public function getAll($filters = [])
    {
        $query = "SELECT u.*, r.name as role_name 
                 FROM users u 
                 LEFT JOIN roles r ON u.role_id = r.id 
                 WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['search'])) {
            $query .= " AND (u.name LIKE ? OR u.email LIKE ? OR u.username LIKE ?)";
            $searchTerm = "%{$filters['search']}%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
        }

        if (!empty($filters['department'])) {
            $query .= " AND u.department = ?";
            $params[] = $filters['department'];
        }
        
        if (!empty($filters['role'])) {
            $query .= " AND u.role_id = ?";
            $params[] = $filters['role'];
        }
        
        if (isset($filters['status'])) {
            $query .= " AND u.active = ?";
            $params[] = $filters['status'];
        }
        
        $query .= " ORDER BY u.name ASC";
        
        return $this->query($query, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cria um novo usuário
     */
    public function create($data)
    {
        // Validações básicas
        $this->validate($data);
        
        // Hash da senha
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        // Dados adicionais
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $_SESSION['user_id'] ?? null;
        $data['active'] = $data['active'] ?? 1;
        
        return $this->insert($data);
    }

    /**
     * Atualiza um usuário existente
     */
    public function update($id, $data)
    {
        // Validações básicas
        $this->validate($data, $id);
        
        // Se uma nova senha foi fornecida
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        
        // Dados adicionais
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $_SESSION['user_id'] ?? null;
        
        return $this->updateById($id, $data);
    }

    /**
     * Alterna o status do usuário
     */
    public function toggleStatus($id)
    {
        $user = $this->find($id);
        if (!$user) {
            throw new \Exception("Usuário não encontrado");
        }

        $newStatus = $user['active'] ? 0 : 1;
        return $this->updateById($id, [
            'active' => $newStatus,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $_SESSION['user_id'] ?? null
        ]);
    }

    /**
     * Valida os dados do usuário
     */
    protected function validate($data, $id = null)
    {
        $errors = [];

        // Nome é obrigatório
        if (empty($data['name'])) {
            $errors[] = "O nome é obrigatório";
        }

        // Username é obrigatório e único
        if (empty($data['username'])) {
            $errors[] = "O nome de usuário é obrigatório";
        } else {
            $query = "SELECT id FROM users WHERE username = ? AND id != ?";
            $result = $this->query($query, [$data['username'], $id ?? 0])->fetch();
            if ($result) {
                $errors[] = "Este nome de usuário já está em uso";
            }
        }

        // Email é obrigatório e único
        if (empty($data['email'])) {
            $errors[] = "O email é obrigatório";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email inválido";
        } else {
            $query = "SELECT id FROM users WHERE email = ? AND id != ?";
            $result = $this->query($query, [$data['email'], $id ?? 0])->fetch();
            if ($result) {
                $errors[] = "Este email já está em uso";
            }
        }

        // Senha é obrigatória apenas na criação
        if (!$id && empty($data['password'])) {
            $errors[] = "A senha é obrigatória";
        }

        // Role é obrigatório
        if (empty($data['role_id'])) {
            $errors[] = "O perfil é obrigatório";
        }

        if (!empty($errors)) {
            throw new \Exception(implode(", ", $errors));
        }
    }

    /**
     * Busca as permissões do usuário
     */
    public function getPermissions($userId)
    {
        $query = "SELECT p.name 
                 FROM permissions p 
                 JOIN role_permissions rp ON p.id = rp.permission_id 
                 JOIN users u ON u.role_id = rp.role_id 
                 WHERE u.id = ?";
        
        $result = $this->query($query, [$userId])->fetchAll(PDO::FETCH_COLUMN);
        return $result;
    }

    /**
     * Atualiza a última data de login
     */
    public function updateLastLogin($id)
    {
        return $this->updateById($id, [
            'last_login' => date('Y-m-d H:i:s')
        ]);
    }
}