<?php

namespace App\Models;

use PDO;
use Exception;

class UserModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllUsers() {
        $query = "
            SELECT 
                u.*, 
                r.name as role_name 
            FROM users u 
            LEFT JOIN user_roles ur ON u.id = ur.user_id
            LEFT JOIN roles r ON ur.role_id = r.id 
            WHERE u.deleted_at IS NULL
            ORDER BY u.name
        ";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllActiveUsers() {
        $query = "
            SELECT id, name 
            FROM users 
            WHERE active = 1 
            AND deleted_at IS NULL
            ORDER BY name
        ";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllPermissions() {
        $query = "SELECT * FROM permissions WHERE active = 1 ORDER BY name";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserPermissions($userId) {
        try {
            error_log('Buscando permissões para o usuário ID: ' . $userId);

            $query = "
                SELECT DISTINCT
                    p.*,
                    CASE WHEN rp.permission_id IS NOT NULL THEN 1 ELSE 0 END as selected
                FROM permissions p
                LEFT JOIN role_permissions rp ON p.id = rp.permission_id
                LEFT JOIN user_roles ur ON rp.role_id = ur.role_id
                WHERE ur.user_id = :user_id
                AND p.active = 1
                ORDER BY p.name
            ";

            $stmt = $this->db->prepare($query);
            $stmt->execute(['user_id' => $userId]);
            $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            error_log('Permissões encontradas: ' . print_r($permissions, true));
            return $permissions;

        } catch (Exception $e) {
            error_log('Erro ao buscar permissões do usuário: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getUserRoles($userId) {
        try {
            $query = "
                SELECT r.*
                FROM roles r
                JOIN user_roles ur ON r.id = ur.role_id
                WHERE ur.user_id = :user_id
                AND r.active = 1
                ORDER BY r.name
            ";

            $stmt = $this->db->prepare($query);
            $stmt->execute(['user_id' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log('Erro ao buscar roles do usuário: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createUser($data) {
        try {
            $this->db->beginTransaction();

            // Inserir usuário
            $stmt = $this->db->prepare("
                INSERT INTO users (name, email, password, department, active, created_by)
                VALUES (:name, :email, :password, :department, :active, :created_by)
            ");

            $stmt->execute([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'department' => $data['department'],
                'active' => $data['active'] ?? 1,
                'created_by' => $_SESSION['user_id'] ?? null
            ]);

            $userId = $this->db->lastInsertId();

            // Associar role
            if (!empty($data['role_id'])) {
                $stmt = $this->db->prepare("
                    INSERT INTO user_roles (user_id, role_id)
                    VALUES (:user_id, :role_id)
                ");
                $stmt->execute([
                    'user_id' => $userId,
                    'role_id' => $data['role_id']
                ]);
            }

            $this->db->commit();
            return $userId;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Erro ao criar usuário: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateUser($userId, $data) {
        try {
            $this->db->beginTransaction();

            $updateFields = [];
            $params = ['id' => $userId];

            if (isset($data['name'])) {
                $updateFields[] = 'name = :name';
                $params['name'] = $data['name'];
            }

            if (isset($data['email'])) {
                $updateFields[] = 'email = :email';
                $params['email'] = $data['email'];
            }

            if (isset($data['password']) && !empty($data['password'])) {
                $updateFields[] = 'password = :password';
                $params['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }

            if (isset($data['department'])) {
                $updateFields[] = 'department = :department';
                $params['department'] = $data['department'];
            }

            if (isset($data['active'])) {
                $updateFields[] = 'active = :active';
                $params['active'] = $data['active'];
            }

            $updateFields[] = 'updated_by = :updated_by';
            $params['updated_by'] = $_SESSION['user_id'] ?? null;

            if (!empty($updateFields)) {
                $sql = "UPDATE users SET " . implode(', ', $updateFields) . " WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);
            }

            // Atualizar role se fornecida
            if (isset($data['role_id'])) {
                // Remover roles existentes
                $stmt = $this->db->prepare("DELETE FROM user_roles WHERE user_id = ?");
                $stmt->execute([$userId]);

                // Adicionar nova role
                $stmt = $this->db->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
                $stmt->execute([$userId, $data['role_id']]);
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Erro ao atualizar usuário: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteUser($userId) {
        try {
            // Soft delete
            $stmt = $this->db->prepare("
                UPDATE users 
                SET deleted_at = NOW(),
                    updated_by = :updated_by
                WHERE id = :id
            ");
            
            return $stmt->execute([
                'id' => $userId,
                'updated_by' => $_SESSION['user_id'] ?? null
            ]);

        } catch (Exception $e) {
            error_log('Erro ao deletar usuário: ' . $e->getMessage());
            throw $e;
        }
    }
}
